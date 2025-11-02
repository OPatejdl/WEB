<?php

/**
 * class for work with database
 */
class MyDatabase
{
    /** @var PDO $pdo object for work with database*/
    private PDO $pdo;

    /**
     * constructor of MyDatabase class
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8"); // require get data in UTF-8
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
    public function selectFromTable(string $table, string $whereStatement = "", string $orderByStatement = ""): array {
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
    public function deleteFromTable(string $tableName, string $whereStatement): bool{
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        $obj = $this->execQuery($q);

        if($obj == null){
            return false;
        }else{
            return true;
        }
    }

    ///////////////////////// Specific Functions /////////////////////////
    /**
     * Function gets all users from DB order by their username
     *
     * @return array array of all users
     */
    public function getAllUsers(): array {
        return $this->selectFromTable(TABLE_USER, "","username");
    }

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
    public function getAllProductsCategories(): array {
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

    // Get MENU functions

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
     * Functions gets all reviews from DB
     *
     * @return array array of all reviews
     */
    public function getAllReviews(): array {
        return $this->selectFromTable(TABLE_REVIEW);
    }
}
?>