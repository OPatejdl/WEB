<?php
// WEB SETUP

/////// DB Setup //////
    // DB login info
    const DB_SERVER = "localhost";
    const DB_NAME = "kiweb";
    const DB_USER = "root";
    const DB_PASS = "";

    // tables definition
    const TABLE_USER = "opatejdl_user";
    const TABLE_ROLE = "opatejdl_role";
    const TABLE_REVIEW = "opatejdl_review";
    const TABLE_PRODUCT = "opatejdl_product";
    const TABLE_CATEGORY = "opatejdl_category";

    // roles definition
    const ROLE_CONSUMER = "Consumer";
    const ROLE_MANAGER = "Manager";
    const ROLE_ADMIN = "Admin";
    const PRODUCT_PIC_DIR = "data/img/";

/////// Websites Setup //////
    /** Define root address **/
    const ROOT_DIRECTORY = __DIR__;
    /** Controller's Address **/
    const DIRECTORY_CONTROLLERS = ROOT_DIRECTORY . "\app\Controllers";
    /** Model's Address **/
    const DIRECTORY_MODELS = ROOT_DIRECTORY . "\app\Models";
    /** View's Address **/
    const DIRECTORY_VIEW = ROOT_DIRECTORY . "\app\Views";

    /** Default Web Page **/
    const DEFAULT_WEB_PAGE_KEY = "homepage";

    /** All Accessible Web Pages **/
    const WEB_PAGES = array(
        /// Homepage ///
        "homepage" => array(
            "title" => "O nás",

            // controller
            "file_name" => "HomepageController.class.php",
            "class_name" => "HomepageController",
            // Role accessibility
            "access_value" => "0"
        ),

        "reviews" => array(
            "title" => "Recenze",

            // controller
            "file_name" => "ReviewsController.class.php",
            "class_name" => "ReviewsController",
            // Role accessibility
            "access_value" => "0"
        ),

        "menu" => array(
            "title" => "Menu",

            // controller
            "file_name" => "MenuController.class.php",
            "class_name" => "MenuController",
            // Role accessibility
            "access_value" => "0"
        ),

        "login" => array(
            "title" => "Přihlášení",

            // controller
            "file_name" => "LoginController.class.php",
            "class_name" => "LoginController",
            // Role accessibility
            "access_value" => "0"
        ),

        "register" => array(
            "title" => "Registrace",

            // controller
            "file_name" => "RegisterController.class.php",
            "class_name" => "RegisterController",
            // Role accessibility
            "access_value" => "0"
        ),

        "adminPage" => array(
            "title" => "Administrace",

            "file_name" => "AdminPageController.class.php",
            "class_name" => "AdminPageController",

            "access_value" => "15"
        )
    );

    const WEB_MODALS = array(
        "newReview" => array(
            "access_value" => "5"
        ),

        "newProduct" => array(
            "access_value" => "10"
        )
    );
