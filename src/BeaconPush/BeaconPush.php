<?php

/**
 * BeaconPush - API
 *
 * @author venyii
 * @see http://beaconpush.com/guide/
 */
class BeaconPush {

    const API_BASE_URL = 'http://api.beaconpush.com/%s/%s/%s';

    /* commands */
    const CMD_USERS = 'users';
    const CMD_CHANNELS = 'channels';

    /* methods */
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_DELETE = 'DELETE';

    /**
     * The configuration object
     *
     * @var BeaconPushConfiguration
     */
    private $configuration;

    /**
     * The registered channels
     *
     * @var array
     */
    private $channels;

    /**
     * The available commands
     *
     * @var array
     */
    private $commands = array(
        self::CMD_USERS,
        self::CMD_CHANNELS
    );

    /**
     * The available methods
     *
     * @var array
     */
    private $methods = array(
        self::METHOD_POST,
        self::METHOD_GET,
        self::METHOD_DELETE
    );

    /**
     * CTOR
     * 
     * @param BeaconPushConfiguration $configuration
     * @param array $channels
     */
    public function __construct(BeaconPushConfiguration $configuration, array $channels = array()) {
        $this->configuration = $configuration;
        $this->channels = $channels;
    }

    /**
     * Get the configuration object
     * 
     * @return BeaconPushConfiguration
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * Set the configuration object
     * 
     * @param BeaconPushConfiguration $configuration
     */
    public function setConfiguration(BeaconPushConfiguration $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * Get the registered channels
     * 
     * @return array
     */
    public function getChannels() {
        return $this->channels;
    }

    /**
     * Set a collection of channels
     * 
     * @param array $channels
     */
    public function setChannels(array $channels) {
        $this->channels = $channels;
    }

    /**
     * Append a channel to the collection
     * 
     * @param string $channel
     */
    public function appendChannel($channel) {
        if (false === $this->existsChannel($channel)) {
            $this->channels[] = $channel;
        }
    }

    /**
     * Remove a channel from the collection
     * 
     * @param string $channel
     */
    public function removeChannel($channel) {
        if (false !== ($channelId = $this->existsChannel($channel))) {
            unset($this->channels[$channelId]);
        }
    }

    /**
     * Check if the channel exists in the collection. If so, return the key.
     * 
     * @param string $channel
     * @return int|bool
     */
    public function existsChannel($channel) {
        return array_search($channel, $this->channels);
    }

    /**
     * Check if the passed method is valid
     * 
     * @param string $method
     * @return bool
     */
    protected function isValidMethod($method) {
        return in_array($method, $this->methods);
    }

    /**
     * Check if the passed command is valid
     * 
     * @param string $cmd
     * @return bool
     */
    protected function isValidCommand($cmd) {
        return in_array($cmd, $this->commands);
    }

    /**
     * Push data to a channel
     * 
     * @param string $channel
     * @param string $eventName
     * @param array $data
     */
    public function pushToChannel($channel, $eventName, array $data = array()) {
        return $this->doRequest(self::CMD_CHANNELS, self::METHOD_POST, $channel, array('name' => $eventName, 'data' => $data));
    }

    /**
     * Push data to a collection of channels
     * 
     * @param array $channels
     * @param string $eventName
     * @param array $data
     * @return array
     */
    public function pushToChannels(array $channels, $eventName, array $data = array()) {
        $resultCollection = array();

        foreach ($channels as $channel) {
            $resultCollection[$channel] = $this->doRequest(self::CMD_CHANNELS, self::METHOD_POST, $channel, array('name' => $eventName, 'data' => $data));
        }

        return $resultCollection;
    }

    /**
     * Push data to a user
     * 
     * @param string $user
     * @param string $eventName
     * @param array $data
     */
    public function pushToUser($user, $eventName, array $data = array()) {
        return $this->doRequest(self::CMD_USERS, self::METHOD_POST, $user, array('name' => $eventName, 'data' => $data));
    }

    /**
     * Push data to a collection of users
     * 
     * @param string $users
     * @param string $eventName
     * @param array $data
     * @return array
     */
    public function pushToUsers($users, $eventName, array $data = array()) {
        $resultCollection = array();

        foreach ($users as $user) {
            $resultCollection[$user] = $this->doRequest(self::CMD_USERS, self::METHOD_POST, $user, array('name' => $eventName, 'data' => $data));
        }

        return $resultCollection;
    }

    /**
     * Get all users for a single channel
     * 
     * @param string $channel
     * @return array
     */
    public function getAllUsersForChannel($channel) {
        $result = $this->doRequest(self::CMD_CHANNELS, self::METHOD_GET, $channel);

        return $result['users'];
    }

    /**
     * Get all users for a collection of channels
     * 
     * @param array $channels
     * @return array
     */
    public function getAllUsersForChannels(array $channels) {
        $resultCollection = array();

        foreach ($channels as $channel) {
            $resultCollection[] = $this->getUsersForChannel($channel);
        }

        return $resultCollection;
    }

    /**
     * Check if the passed user is online
     * 
     * @param string $user
     * @return bool
     */
    public function isUserOnline($user) {
        $result = $this->doRequest(self::CMD_USERS, self::METHOD_GET, $user);

        return isset($result['online']);
    }

    /**
     * Get the number of online users
     * 
     * @return int
     */
    public function getUserOnlineCount() {
        $result = $this->doRequest(self::CMD_USERS, self::METHOD_GET);

        return (int) $result['online'];
    }

    /**
     * Force a logout for the passed user
     * 
     * @param string $user
     * @return string
     */
    public function forceUserLogout($user) {
        return $this->doRequest(self::CMD_USERS, self::METHOD_DELETE, $user);
    }

    /**
     * Execute the request
     * 
     * @param string $cmd
     * @param string $method
     * @param string $param
     * @param array $data
     * @return string
     * @throws BeaconPushException
     */
    protected function doRequest($cmd, $method, $param = null, array $data = array()) {
        if (!$this->isValidMethod($method)) {
            throw new BeaconPushException(sprintf('Method \'%s\' not found.', $method));
        }
        if (!$this->isValidCommand($cmd)) {
            throw new BeaconPushException(sprintf('Command \'%s\' not found.', $cmd));
        }

        $apiUrl = sprintf(self::API_BASE_URL, $this->getConfiguration()->getApiVersion(), $this->getConfiguration()->getApiKey(), $cmd);

        if (null !== $param) {
            if (is_array($param)) {
                $apiUrl .= '/' . implode('/', $param);
            } else {
                $apiUrl .= '/' . $param;
            }
        }

        $headers = array(
            'X-Beacon-Secret-Key: ' . $this->getConfiguration()->getApiSecret(),
        );

        $ch = curl_init($apiUrl);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->getConfiguration()->getRequestTimeout());
        curl_setopt($ch, CURLOPT_VERBOSE, 0);

        switch ($method) {
            case self::METHOD_POST:
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case self::METHOD_GET:
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case self::METHOD_DELETE:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
                break;
        }

        $result = curl_exec($ch);

        if (false === $result) {
            throw new BeaconPushException('cURL request failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return ('' !== $result) ? json_decode($result) : null;
    }

    /**
     * Get the HTML code to embed
     * 
     * @return string
     */
    public function toHTML() {
        $html = <<<BEACONPUSH
<script type="text/javascript" src="http://cdn.beaconpush.com/clients/client-1.js"></script>
<script type="text/javascript">
    Beacon.connect('%s', %s, %s);
</script>
BEACONPUSH;

        return sprintf($html, $this->getConfiguration()->getApiKey(), json_encode($this->getChannels()), json_encode($this->getConfiguration()->getOptionsHash()));
    }

    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->toHTML();
    }

}
