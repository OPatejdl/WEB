<?php

/**
 * class for work with database
 */
class MyDatabase
{
    /** @var PDO $pdo object for work with database*/
    private PDO $pdo;

    /** @var MySession $session object for session handling */
    private MySession $session;


    /**
     * constructor of MyDatabase class
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8"); // require get data in UTF-8

        require_once("MySession.class.php");
        $session = new MySession();
    }

    ///////////////////////// General Functions /////////////////////////
    /**
     * Function executes the query
     *
     * @param string $q
     * @return PDOStatement|null returns received data, if exists, otherwise null
     */
    private function execQuery(string $q): PDOStatement|null {
        $res = $this->pdo->query($q);

        if (!$res) {
            $error = $this->pdo->errorInfo()[2];
            echo "<script> console.log('$error');</script>";
            return null;
        } else {
            return $res;
        }
    }

    /**
     * Function used for reading data from certain table
     *
     * @param string $table table's name
     * @param string $whereStatement Optional WHERE statement defining a condition
     * @param string $orderByStatement Optional ORDER_BY statement defining a rule of order
     * @return array Empty array, if no data is found or query fails, otherwise fetched data as an array
     */
    private function selectFromTable(string $table, string $whereStatement = "", string $orderByStatement = ""): array {
        $q = "SELECT * FROM ".$table.
            (($whereStatement == "") ? "" : " WHERE $whereStatement").
            (($orderByStatement == "") ? "" : " ORDER BY $orderByStatement");
        $obj = $this->execQuery($q);

        if (!$obj) {
            return [];
        }

        return $obj->fetchAll();
    }

    /**
     * Function used for deleting data from certain table
     *
     * @param string $tableName table's name
     * @param string $whereStatement WHERE statement defining a condition
     * @return bool returns true if deleted successfully
     */
    private function deleteFromTable(string $tableName, string $whereStatement): bool{
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        $obj = $this->execQuery($q);

        if($obj == null){
            return false;
        }else{
            return true;
        }
    }

    ///////////////////////// Specific Functions /////////////////////////

    /// USER AND ROLES
    /**
     * Function gets all users from DB order by their username
     *
     * @return array array of all users
     */
    public function getAllUsers(): array {
        return $this->selectFromTable(TABLE_USER, "","username");
    }

    public function getAllRoles(): array {
        return $this->selectFromTable(TABLE_ROLE);
    }

    /**
     * Function for getting hashed password for a user
     *
     * @param string $username username of user
     * @return mixed|null null if no password found otherwise hashed password
     */
    public function getHashPassword(string $username) {
        $username = htmlspecialchars($username);

        $q = "SELECT password FROM ".TABLE_USER." WHERE username = :username";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindValue(":username", $username);
        $stmt->execute();

        $hash = $stmt->fetchColumn();
        return $hash ?: null;
    }

    /**
     * Function add new user
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param int $idRole
     * @return bool
     */
    public function addNewUser(string $username, string $password, string $email, int $idRole = 4): bool {
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        $email = htmlspecialchars($email);
        $idRole = htmlspecialchars($idRole);

        $q = "INSERT INTO opatejdl_user (fk_id_role, username, email, password) VALUES
                                         (:id_role, :username, :email, :password)";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(":id_role", $idRole);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /// PRODUCTS AND CATEGORIES
    /**
     * Function gets all products from DB order by their name
     *
     * @return array array of all products
     */
    public function getAllProducts(): array {
        return $this->selectFromTable(TABLE_PRODUCT, "","name");
    }

    /**
     * Function gets all products categories from DB order by their name
     *
     * @return array array of all products
     */
    private function getAllProductsCategories(): array {
        return $this->selectFromTable(TABLE_CATEGORY, "","id_category");
    }

    /**
     * Function gets products of a category
     *
     * @param int $idCategory category's id
     * @return array array of products with same category
     */
    private function getProductsOfCategory(int $idCategory): array {
        return $this->selectFromTable(TABLE_PRODUCT, "fk_id_category = $idCategory");
    }

    ////////////////////////////////////////////////////////
    // MENU functions

    /**
     * Function gets array of products with same category
     *
     * @param array $category - category type: [id_category, name]
     * @return array array in the format [category_name, products]
     */
    private function getMenuOfCategory(array $category): array {
        $subMenu = [];

        $subMenu[0] = $category["name"];
        $subMenu[1] = $this->getProductsOfCategory(($category["id_category"]));

        return $subMenu;
    }

    /**
     * Function gets
     *
     * @return array restaurant's menu
     */
    public function getMenu(): array {
        $menu = [];
        $category = $this->getAllProductsCategories();

        for ($i = 0; $i < count($category); $i++) {
            $menu[$i] = $this->getMenuOfCategory($category[$i]);
        }

        return $menu;
    }

    /**
     * Function gets average rating of a product
     *
     * @param int $idProduct id of a product
     * @return float|null average rating value of certain product
     */
    public function getAvgRating(int $idProduct): float|null {
        $productRating = $this->selectFromTable(TABLE_REVIEW, "fk_id_product = ".$idProduct);
        $totalRating = 0;
        foreach ($productRating as $rating) {
            $totalRating = $totalRating + $rating["rating"];
        }
        $ratings_count = count($productRating);
        if ($ratings_count == 0) {
            return null;
        } else {
            return $totalRating / $ratings_count;
        }
    }

    ////////////////////////////////////////////////////////
    // REVIEW functions
    /**
     * Functions gets all reviews from DB
     *
     * @return array array of all reviews
     */
    public function getAllReviews(): array {
        return $this->selectFromTable(TABLE_REVIEW, "", "created_at");
    }

    private function getReviewsForProduct(int $idProduct): array {
        $idProduct = htmlspecialchars($idProduct);

        $q = "
        SELECT  r.id_review,
                u.username AS user_name,
                p.name AS product_name,
                r.rating, r.description, r.created_at
        FROM ".TABLE_REVIEW." r
        LEFT JOIN ".TABLE_PRODUCT." p ON p.id_product = r.fk_id_product
        LEFT JOIN ".TABLE_USER." u ON u.id_user = r.fk_id_user
        WHERE p.id_product = :productId
        ORDER BY r.created_at ASC;
        ";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":productId", $idProduct);

        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }

        return [];
    }

    public function getAllReviewsFormated(): array {
        $products = $this->getAllProducts();
        $reviews = [];

        foreach ($products as $product) {
            $reviews[$product["name"]] = $this->getReviewsForProduct($product["id_product"]);
        }

        return $reviews;
    }
}
?>