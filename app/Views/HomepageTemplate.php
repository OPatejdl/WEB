<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>
<div class="row align-items-center py-5 mt-5">
    <!-- Info -->
    <div class="col-md-7">
        <h1 class="fw-semibold lh-1"> Vítejte na stránce Hospůdky <br> U Dvou Piváků </h1>
        <p class="lead">Hospůdka U Dvou Piváků je útulné místo v Plzni, kde se potkává poctivé pivo, česká kuchyně a přátelská atmosféra. Nabízíme denní menu, posezení uvnitř i na venkovní terase a příjemné prostředí pro setkání s rodinou či přáteli.</p>
    </div>

    <!-- Picture -->
    <div class="col-md-5 d-flex justify-content-end align-items-center">
        <img alt="Obrázek hospůdky"
             src="data/foto_hospoda.png"
             class="img-fluid rounded"
             style="height: auto;
                    object-fit: cover;
                    width: 100%;"
        >
    </div>
</div>
<?php
    $tmplHeaders->getHTMLFooter();

?>
