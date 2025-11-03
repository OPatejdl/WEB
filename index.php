<?php
    // web setup
    require_once("settings.inc.php");
    require_once("app/AppStart.class.php");

    $app = new AppStart();
    $app->appStart();
