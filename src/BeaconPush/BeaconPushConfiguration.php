<?php

/**
 * BeaconPush - Configuration
 *
 * @author venyii
 */
class BeaconPushConfiguration {

    /**
     * The Beacon API version
     *
     * @var string
     */
    private $apiVersion = '1.0.0';

    /**
     * Your Beacon API Key
     *
     * @var string
     */
    private $apiKey;

    /**
     * Your Beacon API Secret
     *
     * @var string
     */
    private $apiSecret;

    /**
     * Is logging enabled?
     *
     * @var bool
     */
    private $loggingEnabled = false;

    /**
     * The user name
     *
     * @var mixed
     */
    private $user;

    /**
     * The request timeout (30s)
     *
     * @var int
     */
    private $requestTimeout = 30;

    /**
     * Get the Beacon API Version
     * 
     * @return string
     */
    public function getApiVersion() {
        return $this->apiVersion;
    }

    /**
     * Set the Beacon API Version
     * 
     * @param string $apiVersion
     * @return \BeaconPush\Configuration
     */
    public function setApiVersion($apiVersion) {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * Get the Beacon API Key
     * 
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * Set the Beacon API Key
     * 
     * @param string $apiKey
     * @return \BeaconPush\Configuration
     */
    public function setApiKey($apiKey) {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the Beacon API Secret
     * 
     * @return string
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

    /**
     * Set the Beacon API Secret
     * 
     * @param string $apiSecret
     * @return \BeaconPush\Configuration
     */
    public function setApiSecret($apiSecret) {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    /**
     * Check if logging is enabled
     * 
     * @return bool
     */
    public function getLoggingEnabled() {
        return $this->loggingEnabled;
    }

    /**
     * Set if logging is enabled
     * 
     * @param bool $loggingEnabled
     * @return \BeaconPushConfiguration
     */
    public function setLoggingEnabled($loggingEnabled) {
        $this->loggingEnabled = (bool) $loggingEnabled;

        return $this;
    }

    /**
     * Get the user name
     * 
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set the user name
     * 
     * @param mixed $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * Get the request timeout
     * 
     * @return int
     */
    public function getRequestTimeout() {
        return $this->requestTimeout;
    }

    /**
     * Set the request timeout
     * 
     * @param int $requestTimeout
     * @return \BeaconPushConfiguration
     */
    public function setRequestTimeout($requestTimeout) {
        $this->requestTimeout = (int) $requestTimeout;

        return $this;
    }

    /**
     * Get a hash for the JS options
     * 
     * @return array
     */
    public function getOptionsHash() {
        return array(
            'log' => $this->getLoggingEnabled(),
            'user' => $this->getUser()
        );
    }

}
