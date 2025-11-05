<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class MenuController implements IController {

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

        $tplData["isLogged"] = $this->db->isUserLoggedIn();
        if ($tplData["isLogged"]) {
            $tplData["user"] = $this->db->getLoggedUserData();
        }

        $tplData["menu"] = $this->db->getMenu();

        $tplData["products"] = $this->db->getAllProducts();
        $tplData["categories"] = $this->db->getAllCategories();

        foreach ($tplData["products"] as $product) {
            $tplData[htmlspecialchars($product["id_product"])."_rating"] =
                $this->db->getAvgRating($product["id_product"]);
        }

        ob_start();

        require(DIRECTORY_VIEW . "/MenuTemplate.php");

        return ob_get_clean();
    }
}
?>