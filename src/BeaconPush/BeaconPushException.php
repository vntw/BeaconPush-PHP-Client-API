<?php

/**
 * BeaconPush - Exception
 *
 * @author venyii
 */
class BeaconPushException extends Exception {

    /**
     * CTOR
     * 
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = null, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
