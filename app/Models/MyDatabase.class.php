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
            echo $this->pdo->errorInfo()[2];
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
     * Functions gets all users from DB order by their username
     *
     * @return array array of all users
     */
    public function getAllUsers(): array {
        return $this->selectFromTable(TABLE_USER, "","username");
    }

    /**
     * Functions gets all products from DB order by their name
     *
     * @return array array of all products
     */
    public function getAllProducts(): array {
        return $this->selectFromTable(TABLE_PRODUCT, "","name");
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