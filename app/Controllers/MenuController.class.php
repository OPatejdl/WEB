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
        $tplData["priorities"] = $this->db->getAllPriorities();

        foreach ($tplData["products"] as $product) {
            $tplData[htmlspecialchars($product["id_product"])."_rating"] =
                $this->db->getAvgRating($product["id_product"]);
        }

        if (isset($_POST["action"])) {
            switch ($_POST["action"]) {
                case "newProduct":
                    if (isset($_POST["newProduct_name"]) && isset($_FILES["newProduct_pic"])
                    && isset($_POST["newProduct_price"]) && isset($_POST["newProduct_category"])) {
                        $newName = $_POST["newProduct_name"];
                        $newPic = basename($_FILES["newProduct_pic"]["name"]);
                        $newPrice = $_POST["newProduct_price"];
                        $newCategory = $_POST["newProduct_category"];

                        if ($this->db->doesCategoryExist($newCategory)) {
                            if ($newName != "" && $newPic != "" && $newPrice != "") {
                                $photo = date("YmdHis").$tplData["user"]["username"]."-";
                                $target_file = PRODUCT_PIC_DIR . $photo . $newPic;
                                $res = $this->db->addNewProduct($newName, $target_file, $newPrice, $newCategory);
                                if ($res) {
                                    header('Location: ' . $_SERVER['REQUEST_URI']);
                                    exit();
                                } else {
                                    echo "<script> console.log('New Product - Fail on DB') </script>";
                                }
                            } else {
                                echo "<script> console.log('New Product - Some of the input is null') </script>";
                            }
                        } else {
                            echo "<script> console.log('New Product - Unknown category Id') </script>";
                        }
                    }
                    break;

                case "deleteProduct":
                    if (isset($_POST["del_productId"]) && is_numeric($_POST["del_productId"])) {
                        if ($this->db->doesProductExist($_POST["del_productId"])) {
                            $res = $this->db->deleteProduct($_POST["del_productId"]);
                            if ($res) {
                                header('Location: ' . $_SERVER['REQUEST_URI']);
                                exit();
                            } else {
                                echo "<script> console.log('Delete Product - Fail on DB') </script>";
                            }
                        } else {
                            echo "<script> console.log('Delete Product - Id not exist') </script>";
                        }

                    } else {
                        echo "<script> console.log('Delete Product - Undef ID') </script>";
                    }
                    break;

                case "editProduct":
                    if (isset($_POST["editProduct_name"]) && isset($_POST["editProduct_price"])
                        && isset($_POST["editProduct_category"]) && isset($_POST["productId"])) {
                        // Set data
                        $editName = $_POST["editProduct_name"];
                        $editPrice = $_POST["editProduct_price"];
                        $editCategory = $_POST["editProduct_category"];
                        $picData = $this->db->getProductPicById($_POST["productId"]);
                        $current_pic_path = $picData["photo_url"];

                        if ($this->db->doesCategoryExist($editCategory)) {
                            if ($editName != "" && $editPrice != "") {

                                // set target_file
                                if ($_FILES["editProduct_pic"]["name"] != "") {
                                    if (file_exists($current_pic_path)) {
                                        unlink($current_pic_path);
                                    }
                                    $editPic = basename($_FILES["editProduct_pic"]["name"]);
                                    $photo = date("YmdHis").$tplData["user"]["username"]."-";
                                    $target_file = PRODUCT_PIC_DIR . $photo . $editPic;
                                } else {
                                    $target_file = $current_pic_path;
                                }

                                $res = $this->db->editProduct($_POST["productId"], $editName, $target_file, $editPrice, $editCategory);
                                if ($res) {
                                    header('Location: ' . $_SERVER['REQUEST_URI']);
                                    exit();
                                } else {
                                    echo "<script> console.log('Edit Product - Fail on DB') </script>";
                                }
                            } else {
                                echo "<script> console.log('Edit Product - Some of the input is null') </script>";
                            }
                        } else {
                            echo "<script> console.log('Edit Product - Unknown category Id') </script>";
                        }
                    } else {
                        echo "<script> console.log('Edit Product - Undef Value') </script>";
                    }
                    break;

                default:
                    echo "<script> console.log('Menu page - Unknown action') </script>";
            }
        }

        ob_start();

        require(DIRECTORY_VIEW . "/MenuTemplate.php");

        return ob_get_clean();
    }
}