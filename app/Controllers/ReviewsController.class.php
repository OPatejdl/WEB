<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class Reviews implements IController
{
    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    /**
     * Reviews class constructore
     */
    public function __construct() {
        require_once(DIRECTORY_MODELS . "/MyDatabase.class.php");
        $this->db = new MyDatabase();
    }

    public function show(string $pageTitle): string
    {
        global $tplData;

        $tplData= [];
        $tplData["title"] = $pageTitle;
        $tplData["isLogged"] = true;

        $tplData["reviews"] = $this->db->getAllReviewsFormated();
        $tplData["products"] = $this->db->getAllProducts();

        ob_start();
        require(DIRECTORY_VIEW . "/ReviewsTemplate.php");

        return ob_get_clean();
    }
}