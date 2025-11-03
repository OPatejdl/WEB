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
        $tplData["user"] = [];
        $tplData["error"] = "";

        // Login action
        if (isset($_POST['action'])) {
            if ($_POST['action'] == "login" && isset($_POST['username']) && isset($_POST['password'])) {
                if (password_verify($_POST['password'], $this->db->getHashedPassword($_POST['username']))) {
                    $res = $this->db->loginUser($_POST["username"]);
                    if (!$res) {
                        $tplData["error"] = "Neplatné uživatelské jméno nebo heslo";
                    }
                } else {
                    $tplData["error"] = "Neplatné uživatelské jméno nebo heslo";
                }
            } elseif ($_POST["action"] == "logout") {
                $this->db->logoutUser();
            }
        }

        $tplData["isLogged"] = $this->db->isUserLoggedIn();
        if ($tplData["isLogged"]) {
            $tplData["user"] = $this->db->getLoggedUserData();
            $user = $tplData["user"];
            $tplData["user_reviews"] = $this->db->getUserReviews($user["id_user"]);
        }

        ob_start();

        require(DIRECTORY_VIEW . "/LoginTemplate.php");

        return ob_get_clean();
    }
}