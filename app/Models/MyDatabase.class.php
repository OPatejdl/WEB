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

    /** @var string $userSessionKey key for user data saved in the session */
    private string $userSessionKey = "current_user_id";


    /**
     * constructor of MyDatabase class
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8"); // require get data in UTF-8

        require_once("MySession.class.php");
        $this->session = new MySession();
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

    ///////////////////////// Specific Functions /////////////////////////

    /// USER AND ROLES
    /**
     * Function gets all users from DB order by their username
     * without super admin
     *
     * @return array array of all users
     */
    public function getAllUsersForAdmin(): array {
        return $this->selectFromTable(TABLE_USER, "fk_id_role != 1","username");
    }

    public function getUsersReviewsCount(string $userId): int {
        $userId = htmlspecialchars($userId);

        $q = "SELECT COUNT(*) FROM ". TABLE_REVIEW ." WHERE fk_id_user = :userId";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":userId", $userId);

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function getAllRoles(): array {
        return $this->selectFromTable(TABLE_ROLE);
    }

    /**
     * Function gets all possible categories for product
     *
     * @return array products categories
     */
    public function getAllCategories(): array {
        return $this->selectFromTable(TABLE_CATEGORY);
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
     * @param int $productId id of a product
     * @return float|null average rating value of certain product
     */
    public function getAvgRating(int $productId): float|null {
        $productRating = $this->selectFromTable(TABLE_REVIEW, "fk_id_product = ".$productId);
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

    /**
     * Function gets reviews of product
     *
     * @param int $productId - product id
     * @return array array of reviews
     */
    private function getReviewsForProduct(int $productId): array {
        $productId = htmlspecialchars($productId);

        $q = "
        SELECT  r.id_review,
                u.username AS user_name,
                p.name AS product_name,
                r.rating, r.description, r.created_at, r.publicity
        FROM ".TABLE_REVIEW." r
        LEFT JOIN ".TABLE_PRODUCT." p ON p.id_product = r.fk_id_product
        LEFT JOIN ".TABLE_USER." u ON u.id_user = r.fk_id_user
        WHERE p.id_product = :productId
        ORDER BY r.created_at ASC;
        ";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":productId", $productId);

        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }

        return [];
    }

    /**
     * Function gets all reviews based on their product
     *
     * @return array formated array of reviews
     */
    public function getAllReviewsFormated(): array {
        $products = $this->getAllProducts();
        $reviews = [];

        foreach ($products as $product) {
            $reviews[$product["name"]] = $this->getReviewsForProduct($product["id_product"]);
        }

        return $reviews;
    }

    /**
     * Function gets user's reviews for certain product
     *
     * @param int $productId
     * @param int $idUser
     * @return array
     */
    private function getUserReviewsForProduct(int $productId, int $idUser): array {
        $idUser = htmlspecialchars($idUser);

        $q = "SELECT 
                    r.id_review ,r.rating, r.description, r.publicity,
                    r.fk_id_product
                FROM ".TABLE_REVIEW." r 
                WHERE fk_id_user = :idUser AND fk_id_product = :idProduct 
                ORDER BY r.created_at ASC";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->bindValue(":idProduct", $productId);

        if ($stmt->execute()) {
            return $stmt->fetchAll();
        }

        return [];
    }

    /**
     * Function gets user's reviews
     *
     * @param int $idUser - user's id
     * @return array array of reviews created by user with $idUser
     */
    public function getUserReviews(int $idUser): array {
        $products = $this->getAllProducts();
        $reviews = [];
        foreach ($products as $product) {
            $productReviews = $this->getUserReviewsForProduct($product["id_product"], $idUser);

            if (!empty($productReviews)) {
                $reviews[$product["name"]] = $productReviews;
            }
        }

        return $reviews;
    }

    /////////////////////////////////////////////////
    /// USER LOGIN FUNCTION

    /**
     * Function to inform if a user is logged
     *
     * @return bool true if user is logged otherwise false
     */
    public function isUserLoggedIn(): bool {
        return $this->session->isSessionSet($this->userSessionKey);
    }

    /**
     * Function for getting hashed password for a user
     *
     * @param string $username username of user
     * @return mixed|null null if no password found otherwise hashed password
     */
    public function getHashedPassword(string $username): mixed{
        $username = htmlspecialchars($username);

        $q = "SELECT password FROM ".TABLE_USER." WHERE username = :username";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindValue(":username", $username);
        $stmt->execute();

        $hash = $stmt->fetchColumn();
        return $hash ?: null;
    }

    /**
     * Function logs in a user
     *
     * @param string $username user's name
     * @return bool returns true if user exists otherwise false
     */
    public function loginUser(string $username): bool {
        $username = htmlspecialchars($username);

        $q = "SELECT * FROM ".TABLE_USER." WHERE username = :username";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindValue(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->session->addSession($this->userSessionKey, $row["id_user"]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function log's out user
     */
    public function logoutUser(): void {
        $this->session->removeSession($this->userSessionKey);
    }

    /**
     * Function to get data for logged user
     *
     * @return array|null data about user if any exists
     */
    public function getLoggedUserData(): ?array
    {
        if (!$this->isUserLoggedIn()) {
            echo "<script> console.log(`SERVER ERROR: No user logged in`);</script>";
            return null;
        }
        $userId = $this->session->getSession($this->userSessionKey);
        if ($userId == null) {
            echo "<script> console.log(`SERVER ERROR: User's ID in SESSION was null`);</script>";
            $this->logoutUser();
            return null;
        } else {
            $userId = htmlspecialchars($userId);

            $q = "SELECT 
                        u.id_user, u.username, u.email, u.created_at,
                        r.name AS role,
                        r.priority AS priority
                    FROM ".TABLE_USER." u
                    LEFT JOIN ".TABLE_ROLE." r ON r.id_role = u.fk_id_role
                    WHERE u.id_user = :userId";

            $stmt = $this->pdo->prepare($q);
            $stmt->bindValue(":userId", $userId);

            if ($stmt->execute()) {
                $userData = $stmt->fetch();
            } else{
                $userData = [];
            }

            if (empty($userData)) {
                echo "<script> console.log(`SERVER ERROR: User's data are null`);</script>";
                $this->logoutUser();
                return null;
            }

            return $userData;
        }
    }

    //////////////////////////////////////////////////////
    /// Register Checks

    /**
     * Function checks if username exists or not
     *
     * @param string $username evaluating username
     * @return bool true if exists otherwise false
     */
    public function isUsernameTaken(string $username): bool {
        $username = htmlspecialchars($username);

        $q = "SELECT username FROM ".TABLE_USER." WHERE username = :username";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":username", $username);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Function checks if email exists or not
     *
     * @param string $email evaluating email
     * @return bool true if exists otherwise false
     */
    public function isEmailTaken(string $email): bool {
        $email = htmlspecialchars($email);

        $q = "SELECT email FROM ".TABLE_USER." WHERE email = :email";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":email", $email);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    //////////////////////////////////////////////////////
    /// New Review Checks
    /**
     * Function checks if product's with productId exists or not
     *
     * @param int $productId product's id for check
     * @return bool true if exists otherwise false
     */
    public function productIdExists(int $productId): bool {
        $productId = htmlspecialchars($productId);

        $q = "SELECT name FROM ".TABLE_PRODUCT." WHERE id_product = :productId";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":productId", $productId);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }


    public function addNewReview(string $id_user, string $id_product, string $rating, string $description, int $publicity = 1): bool {
        $idUser = htmlspecialchars($id_user);
        $productId = htmlspecialchars($id_product);
        $rating = htmlspecialchars($rating);
        $description = htmlspecialchars($description);
        $publicity = htmlspecialchars($publicity);

        $q = "INSERT INTO opatejdl_review (fk_id_user, fk_id_product, rating, description, publicity) VALUES
                                         (:id_user, :id_product, :rating, :description, :publicity)";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":id_user", $idUser);
        $stmt->bindValue(":id_product", $productId);
        $stmt->bindValue(":rating", $rating);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":publicity", $publicity);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Function gets all priorities in the DB
     *
     * @return array array of priorities based on their role
     */
    public function getAllPriorities(): array {
        $priorities = [];

        $allPriorities = $this->selectFromTable(TABLE_ROLE);

        foreach ($allPriorities as $priority) {
            $priorities[$priority["name"]] = $priority["priority"];
        }

        return $priorities;
    }

    /**
     * Function checks if a review of product from the user exists
     *
     * @param string $id_user user's id
     * @param string $id_product product's id
     * @return bool true if review on certain product from the user exists otherwise false
     */
    public function checkIfReviewExists(string $id_user, string $id_product): bool {
        $idUser = htmlspecialchars($id_user);
        $productId = htmlspecialchars($id_product);

        $q = "SELECT * FROM " . TABLE_REVIEW . " WHERE fk_id_product = :id_product AND fk_id_user = :id_user";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindValue(":id_product", $productId);
        $stmt->bindValue(":id_user", $idUser);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    ////////////////////////////////////////////////////////////////
    /// Change publicity
    /**
     * Function checks if review's with reviewId exists or not
     *
     * @param int $reviewId review's id for check
     * @return bool true if exists otherwise false
     */
    public function reviewIdExists(int $reviewId): bool {
        $reviewId = htmlspecialchars($reviewId);

        $q = "SELECT rating FROM ".TABLE_REVIEW." WHERE id_review = :reviewId";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":reviewId", $reviewId);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function updateReviewPublicity(string $reviewId, int $current): bool {
        $reviewId = htmlspecialchars($reviewId);

        $q = "UPDATE " . TABLE_REVIEW . " SET publicity = :publicity WHERE id_review = :idReview";

        $stmt = $this->pdo->prepare($q);
        if ($current == 0) {
            $stmt->bindValue(":publicity", 1);
        } else {
            $stmt->bindValue(":publicity", 0);
        }

        $stmt->bindValue(":idReview", $reviewId);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    //////////////////////////////////
    /// New Product
    ///

    /**
     * Function checks if category with categoryId exists
     *
     * @param string $categoryId category ID
     * @return bool true if exists otherwise false
     */
    public function doesCategoryExist(string $categoryId): bool {
        $categoryId = htmlspecialchars($categoryId);

        $q = "SELECT name FROM ".TABLE_CATEGORY." WHERE id_category = :category";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":category", $categoryId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function addNewProduct($name, $picPath, $price, $category): bool {
        $name = htmlspecialchars($name);
        $pic = htmlspecialchars($picPath);
        $price = htmlspecialchars($price);
        $category = htmlspecialchars($category);

        $q = "INSERT INTO opatejdl_product (fk_id_category, name, price, photo_url) VALUES
                                        (:category, :name, :price, :pic)";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindValue(":category", $category);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":price", $price);
        $stmt->bindValue(":pic", $pic);

        if ($stmt->execute()) {
            move_uploaded_file($_FILES["newProduct_pic"]["tmp_name"], $picPath);
            return true;
        }

        return false;
    }

    //////////////////////////////////
    /// Delete and Edit Product
    /**
     * Function checks if product with productId exists
     *
     * @param string $productId product ID
     * @return bool true if exists otherwise false
     */
    public function doesProductExist(string $productId): bool {
        $productId = htmlspecialchars($productId);

        $q = "SELECT * FROM ".TABLE_PRODUCT." WHERE id_product = :product";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":product", $productId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Function deletes product with productId from DB
     *
     * @param string $productId
     * @return bool
     */
    public function deleteProduct(string $productId): bool {
        $productId = htmlspecialchars($productId);

        $picData = $this->getProductPicById($productId);

        // remove picture
        if (file_exists($picData["photo_url"])) {
            if (!unlink($picData["photo_url"])) {
                return false;
            }
        }

        $q = "DELETE FROM ".TABLE_PRODUCT." WHERE id_product = :product";
        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":product", $productId);

        return $stmt->execute();
    }

    /**
     * Function gets photo_url of product based on its productId
     *
     * @param string $productId product Id
     * @return array photo_url in array
     */
    public function getProductPicById(string $productId): array {
        $productId = htmlspecialchars($productId);
        $q = "SELECT photo_url FROM ".TABLE_PRODUCT." WHERE id_product = :idProduct";
        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":idProduct", $productId);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Function for product editing
     *
     * @param string $productId product's id
     * @param string $name product's name
     * @param string $picPath path to product's picture
     * @param string $price product's price
     * @param string $category product's category
     * @return bool true if successful otherwise false
     */
    public function editProduct(string $productId, string $name, string $picPath, string $price, string $category): bool {
        $productId = htmlspecialchars($productId);
        $name = htmlspecialchars($name);
        $pic = htmlspecialchars($picPath);
        $price = htmlspecialchars($price);
        $category = htmlspecialchars($category);

        $q = "UPDATE " . TABLE_PRODUCT . " 
              SET 
                fk_id_category = :category,
                name = :name,
                price = :price,
                photo_url = :photo 
              WHERE id_product = :idProduct";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":category", $category);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":price", $price);
        $stmt->bindValue(":photo", $pic);
        $stmt->bindValue(":idProduct", $productId);


        if ($stmt->execute()) {
            move_uploaded_file($_FILES["editProduct_pic"]["tmp_name"], $picPath);
            return true;
        }

        return false;
    }

    ///////////////////////////////////////////
    /// EDIT and DELETE Review

    /**
     * Function checks if review with reviewId exists
     *
     * @param string $reviewId review's id
     * @return bool true if exists otherwise false
     */
    public function doesReviewExist(string $reviewId): bool {
        $reviewId = htmlspecialchars($reviewId);

        $q = "SELECT * FROM ".TABLE_REVIEW." WHERE id_review = :review";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":review", $reviewId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Function deletes review from DB
     *
     * @param string $reviewId review's id
     * @return bool true if successful otherwise false
     */
    public function deleteReview(string $reviewId): bool {
        $reviewId = htmlspecialchars($reviewId);

        $q = "DELETE FROM ".TABLE_REVIEW." WHERE id_review = :review";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":review", $reviewId);
        return $stmt->execute();
    }

    public function editReview(string $reviewId, string $description, string $rating): bool {
        $reviewId = htmlspecialchars($reviewId);
        $description = htmlspecialchars($description);
        $rating = htmlspecialchars($rating);

        $q = "UPDATE " . TABLE_REVIEW . "
              SET
                description = :description,
                rating = :rating
              WHERE id_review = :reviewId";

        $stmt = $this->pdo->prepare($q);
        $stmt->bindValue(":description", $description);
        $stmt->bindValue(":rating", $rating);
        $stmt->bindValue(":reviewId", $reviewId);

        return $stmt->execute();
    }
}