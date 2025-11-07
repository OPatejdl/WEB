<?php
    require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class AdminPageController implements IController
{
    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    /**
     * Constructor for Homepage class
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
        $tplData = [];
        $tplData["title"] = $pageTitle;

        $tplData["isLogged"] = $this->db->isUserLoggedIn();
        if ($tplData["isLogged"]) {
            $tplData["user"] = $this->db->getLoggedUserData();
        }

        $tplData["roles"] = $this->db->getAllRoles();
        $tplData["users"] = $this->db->getAllUsersForAdmin();

        foreach ($tplData["users"] as $user) {
            $tplData[$user["username"]]["reviews_count"] = $this->db->getUsersReviewsCount($user["id_user"]);
        }

        ob_start();
        require(DIRECTORY_VIEW . "/AdminPageTemplate.php");

        // return template with data
        return ob_get_clean();
    }
}