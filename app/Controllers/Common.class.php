<?php

class Common
{
    /** @var MyDatabase $db Var for database handling **/
    private MyDatabase $db;

    /**
     * Reviews class constructor
     */
    public function __construct(MyDatabase $value) {
        $this->db = $value;
    }

    /**
     * Function deletes a review from DB
     *
     * @return void
     */
    public function deleteReview(): void {
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
    }
}