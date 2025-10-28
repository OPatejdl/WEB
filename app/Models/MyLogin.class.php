<?php

/**
 * Class for user's login administration
 */
class MyLogin
{
    /** @var $session MySession Object used for session operating*/
    private $session;
    /** @var $dbName string Key for name session storage*/
    private $dbName = "name";
    /** @var $dbDate string Key for date session storage*/
    private $dbDate = "date";

    public function __construct() {
        include_once("MySession.class.php");
        $this->session = new MySession();
    }

    /**
     * Function checks if user is logged in
     *
     * @return bool
     */
    public function isUserLoggedIn() {
        return $this->session->isSessionSet($this->dbName);
    }

    /**
     * Function logs in a user
     *
     * @param $userName user's name
     */
    public function login($userName) {
        $this->session->addSession($this->dbName, $userName);
        $this->session->addSession($this->dbDate, date("d. m. Y, G:m:s"));
    }

    /**
     * Function logs out a user
     */
    public function logout(){
        $this->session->removeSession($this->dbName);
        $this->session->removeSession($this->dbDate);
    }

    /**
     * Function gets information connected to user
     *
     * @return string information connected to user
     */
    public function getUserInfo(){
        $name = $this->session->getSession($this->dbName);
        $date = $this->session->getSession($this->dbDate);
        return "Jméno: $name<br>Datum: $date<br>";
    }
}

?>