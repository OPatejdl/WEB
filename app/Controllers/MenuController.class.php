<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class Menu implements IController {

    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    /**
     * Menu class constructor
     */
    public function __construct() {
        require_once(DIRECTORY_MODELS . "/MyDatabase.class.php");
        $this->db = new MyDatabase();

    }
    public function show(string $pageTitle): string {
        global $tplData;

        $tplData= [];
        $tplData["title"] = $pageTitle;
        $tplData["isLogged"] = false;

        $tplData["menu"] = $this->db->getMenu();

        ob_start();

        require(DIRECTORY_VIEW . "/MenuTemplate.php");

        return ob_get_clean();
    }
}
?>