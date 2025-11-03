<?php
require_once(DIRECTORY_CONTROLLERS . "/IController.interface.php");

class RegisterController implements IController
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
        $tplData["isLogged"] = $this->db->isUserLoggedIn();
        $tplData["error"] = "";

        $tplData["username"] = "";
        $tplData["email"] = "";

        // Handle registration
        if (isset($_POST["action"])){
            if ($_POST["action"] == "register" && isset($_POST["username"]) && isset($_POST["email"])
                && isset($_POST["password"]) && isset($_POST["password_repeat"])){

                $tplData["username"] = htmlspecialchars($_POST["username"]);
                $tplData["email"] = htmlspecialchars($_POST["email"]);

                if ($_POST["password"] == $_POST["password_repeat"]){

                    if (!$this->db->isUsernameTaken($_POST["username"])){

                        if (!$this->db->isEmailTaken($_POST["email"])){

                            if ($this->db->addNewUser($_POST["username"], password_hash($_POST["password"], PASSWORD_BCRYPT), $_POST["email"])) {

                                $this->db->logoutUser();
                                $tplData["isLogged"] = $this->db->loginUser($_POST["username"]);
                                header("Location: index.php?page=login");
                                exit;

                            } else {
                                $tplData["error"] = "Nepodařilo se tě přidat!<br> Zkus to znovu později!";
                            }
                        } else {
                            $tplData["email"] = "";
                            $tplData["error"] = "Email je již obsazen!";
                        }
                    } else {
                        $tplData["username"] = "";
                        $tplData["error"] = "Username je již obsazené!";
                    }
                } else {
                    $tplData["error"] = "Hesla se neshodují!";
                }
            } else {
                $tplData["error"] = "Vyplňte prosím veškeré informace!";
            }
        }

        ob_start();

        require(DIRECTORY_VIEW . "/RegisterTemplate.php");

        return ob_get_clean();
    }
}