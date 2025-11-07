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
        $tplData["rolesPrior"] = $this->db->getRolesNameAndPriority();

        foreach ($tplData["users"] as $user) {
            $tplData[$user["username"]]["reviews_count"] = $this->db->getUsersReviewsCount($user["id_user"]);
        }

        if (isset($_POST["action"])) {
            switch ($_POST["action"]) {
                case "deleteUser":
                    if (isset($_POST["delete_user_id"]) && is_numeric($_POST["delete_user_id"])) {
                        $res = $this->db->deleteUser($_POST["delete_user_id"]);
                        if ($res) {
                            header('Location: ' . $_SERVER['REQUEST_URI']);
                            exit();
                        } else {
                            echo "<script> console.log('Delete User - DB error') </script>";
                        }
                    } else {
                        echo "<script> console.log('Delete User - Empty id value') </script>";
                    }
                    break;

                case "editUserRole":
                    if (isset($_POST["update_user_id"]) && is_numeric($_POST["update_user_id"])) {
                        if (isset($_POST["new_role_id"]) && is_numeric($_POST["new_role_id"])) {
                            $res = $this->db->editRoleOfUser($_POST["update_user_id"], $_POST["new_role_id"]);
                            if ($res) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                                exit();
                            } else {
                                echo "<script> console.log('Update User - DB error') </script>";
                            }
                        } else {
                            echo "<script> console.log('Update User - Empty new_role value ') </script>";
                        }
                    } else {
                        echo "<script> console.log('Update User - Empty user_id value') </script>";
                    }
                    break;
            }
        }

        ob_start();
        require(DIRECTORY_VIEW . "/AdminPageTemplate.php");

        // return template with data
        return ob_get_clean();
    }
}