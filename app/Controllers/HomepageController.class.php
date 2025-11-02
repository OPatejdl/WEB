<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class HomepageController implements IController
{
    /** @var MyDatabase $db Var for database handling **/
    private $db;

    /**
     * Constructor for Homepage class
     */
    public function __construct() {
        require_once(DIRECTORY_MODELS . "/MyDatabase.class.php");
        $this->db = new MyDatabase();
    }

    public function show(string $pageTitle): string
    {
        global $tplData;
        $tplData = [];
        $tplData["title"] = $pageTitle;
        $tplData["isLogged"] = false;

        ob_start();
        require(DIRECTORY_VIEW . "/HomepageTemplate.php");

        // return template with data
        return ob_get_clean();
    }
}