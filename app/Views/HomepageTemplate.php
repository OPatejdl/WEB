<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
    echo "<h1>Ahoj svete</h1>";
    $tmplHeaders->getHTMLFooter();

?>
