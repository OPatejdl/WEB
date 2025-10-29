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
            "title" => "Homepage",

            // controller
            "file_name" => "Homepage.class.php",
            "class_name" => "Homepage",
            // Role accessibility
            "access_value" => "0"
        )
    );
?>