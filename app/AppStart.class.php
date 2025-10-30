<?php

class AppStart
{
    /**
     * Web initialization
     */
    function __construct() {
        require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
    }

    /**
     * Function starts the web application
     */
    public function appStart(): void {
        if(isset($_GET["page"]) && array_key_exists($_GET["page"], WEB_PAGES)) {
            $pageKey = $_GET["page"];
        } else {
            $pageKey = DEFAULT_WEB_PAGE_KEY;
        }

        // data of controller
        $pageInfo = WEB_PAGES[$pageKey];

        require_once(DIRECTORY_CONTROLLERS . "/" . $pageInfo["file_name"]);

        /** @var IController $controller controller of certain page*/
        $controller = new $pageInfo["class_name"];

        echo $controller->show($pageInfo["title"]);
    }
}
?>