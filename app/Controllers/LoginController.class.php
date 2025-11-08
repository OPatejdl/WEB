<?php

require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");

class LoginController implements IController
{

    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    private Common $commonFunc;

    /**
     * Login class constructor
     */
    public function __construct() {
        require_once(DIRECTORY_MODELS . "/MyDatabase.class.php");
        $this->db = new MyDatabase();

        require_once(DIRECTORY_CONTROLLERS . "/Common.class.php");
        $this->commonFunc = new Common($this->db);
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

        // Action handling
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
                    $this->commonFunc->deleteReview();
                    break;

                case "editReview":
                    if (isset($_POST["reviewId"]) && isset($_POST["editReview_Rating"])
                        && isset($_POST["editReview_Description"])) {

                        $reviewId = $_POST["reviewId"];
                        $newDes = $_POST["editReview_Description"];
                        $newRating = $_POST["editReview_Rating"];

                        if ($newDes != "" && $newRating != "" && is_numeric($newRating)
                            && $reviewId != "" && is_numeric($reviewId)) {
                            $res = $this->db->editReview($reviewId, $newDes, $newRating);
                            if ($res) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                                exit();
                            } else {
                                echo "<script> console.log('Edit Review - Fail on DB') </script>";
                            }
                        } else {
                            echo "<script> console.log('Edit Review - Some of the input is null') </script>";
                        }
                    } else {
                        echo "<script> console.log('Edit Review - Undef ID') </script>";
                    }
                    break;
            }
        }

        // check if user is 
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