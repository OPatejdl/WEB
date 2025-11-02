<?php

require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");

class LoginController implements IController
{

    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    /**
     * Login class constructor
     */
    public function __construct() {
        require_once(DIRECTORY_MODELS . "/MyDatabase.class.php");
        $this->db = new MyDatabase();
    }

    /**
     * @inheritDoc
     */
    public function show(string $pageTitle): string
    {
        global $tplData;

        $tplData= [];
        $tplData["title"] = $pageTitle;
        $tplData["isLogged"] = false;

        ob_start();

        require(DIRECTORY_VIEW . "/LoginTemplate.php");

        return ob_get_clean();
    }
}