<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");
class ReviewsController implements IController
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
        $tplData["isLogged"] = $this->db->isUserLoggedIn();
        if ($tplData["isLogged"]) {
            $tplData["user"] = $this->db->getLoggedUserData();
        }

        $tplData["reviews"] = $this->db->getAllReviewsFormated();
        $tplData["products"] = $this->db->getAllProducts();

        if (isset($_POST["action"])) {

            if ($_POST["action"] == "newReview" && isset($_POST["newReview_Product"])
                && isset($_POST["newReview_Description"]) && isset($_POST["newReview_Rating"])) {

                if ($this->db->productIdExists((int)$_POST["newReview_Product"])) {
                    $rating = (float)$_POST["newReview_Rating"];
                    if ($rating >= 0 && $rating <= 5) {

                        if ($_POST["newReview_Description"] != "") {
                            $res = $this->db->addNewReview($tplData["user"]["id_user"], $_POST["newReview_Product"],
                                                            $_POST["newReview_Rating"], $_POST["newReview_Description"]);
                            if ($res) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                                exit();
                            } else {
                                echo "<script> console.log('New Review - Fail on DB') </script>";
                            }

                        } else {
                            echo "<script> console.log('New Review - Empty Description') </script>";
                        }

                    } else {
                        echo "<script> console.log('New Review - Rating needs to be >= 0 and <= 5') </script>";
                    }

                } else {
                    echo "<script> console.log('New Review - Product with this Id does not exist') </script>";
                }

            } else {
                echo "<script> console.log('New Review - No data!') </script>";
            }
        }

        ob_start();
        require(DIRECTORY_VIEW . "/ReviewsTemplate.php");

        return ob_get_clean();
    }
}