<?php

/**
 * Class for Session handling
 *
 * @author: Ondrej Patejdl
 */
class MySession
{
    public function __construct() {
        session_start();
    }

    /**
     * Function adds value to certain session
     *
     * @param $name  attribute's name
     * @param $value attribute's value
     */
    public function addSession($name, $value): void {
        $_SESSION[$name] = $value;
    }

    /**
     * Function gets certain session's data
     *
     * @param $name session's name
     * @return mixed|null
     */
    public function getSession($name): ?string {
        if ($this->isSessionSet($name)) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    /**
     * Functions checks if session exists
     *
     * @param $name session's name
     * @return bool true if session is set otherwise false
     */
    public function isSessionSet($name): bool {
        return isset($_SESSION[$name]);
    }

    /**
     * Function removes a session
     *
     * @param $name session's name
     */
    public function removeSession($name): void {
        unset($_SESSION[$name]);
    }
}