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
        $tplData["products"] = $this->db->getAllProducts();

        // Login action
        if (isset($_POST["action"])) {
            switch ($_POST["action"]) {
                case "login":
                    if (isset($_POST["username"]) && isset($_POST["password"])) {
                        if (password_verify($_POST["password"], $this->db->getHashedPassword($_POST["username"]))) {
                            $res = $this->db->loginUser($_POST["username"]);
                            if (!$res) {
                                $tplData["error"] = "Neplatné uživatelské jméno nebo heslo";
                            }
                        } else {
                            $tplData["error"] = "Neplatné uživatelské jméno nebo heslo";
                        }
                    }
                    break;

                case "logout":
                    $this->db->logoutUser();
                    break;

                case "deleteReview":
                    if (isset($_POST["del_reviewId"]) && is_numeric($_POST["del_reviewId"])) {
                        if ($this->db->doesReviewExist($_POST["del_reviewId"])) {
                            $res = $this->db->deleteReview($_POST["del_reviewId"]);
                            if ($res) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                                exit();
                            } else {
                                echo "<script> console.log('Delete Review - Fail on DB') </script>";
                            }
                        } else {
                            echo "<script> console.log('Delete Review - Id not exist') </script>";
                        }

                    } else {
                        echo "<script> console.log('Delete Review - Undef ID') </script>";
                    }
                    break;

                case "editReview":
                    break;
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