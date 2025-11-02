<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    // HEADER
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<!-- Info -->
<div class="row align-items-center justify-content-center py-5 mt-5">
    <div class="col">
        <h1> Recenze </h1>
    </div>
    <?php
        $btn_view = "";
        if ($tplData["isLogged"]) {
            $btn_view = "
            <div class='col-auto'>
                <form method='post'>
                    <input type='hidden' name='newReview' value=''>
                    <button type='submit' class='btn btn-dark'>Napsat Recenzi</button>
                </form>
            </div>";
        }
        echo $btn_view;
    ?>
</div>

<!-- Reviews -->
<?php
    $reviews_view = "";

    foreach ($tplData["products"] as $product) {
        if (isset($tplData["reviews"][$product["name"]]) && $tplData["reviews"][$product["name"]] != null) {
            $reviews_view .= "
            <section class='myBox-section card border-0 shadow-sm mb-5 w-100'>
              <div class='card-header bg-light'>
                <h2 class='mt-2 mb-2'>".$product["name"]."</h2>
              </div>
              <div class='card-body p-0'>
                <ul class='list-group list-group-flush'>
            ";
            foreach ($tplData["reviews"][$product["name"]] as $review) {
                $stars = $tmplHeaders->setRatingSyle($review["rating"]);
                $reviews_view .= "
                <li class='list-group-item'>
                    <div class='col'>
                        <h5 class='mb-1'>".$review["user_name"]."</h5>
                        <ul class='list-unstyled text-muted small mb-0'>
                          <li> <i>".$review["description"]."</i></li>
                          <li><strong>Hodnocení:</strong>".$stars."</li>
                        </ul>
                </li>
                ";
            }
            $reviews_view .= "
                </ul>
              </div>
            </section>
            ";
        }
    }

    echo $reviews_view;
?>



<?php
    // FOOTER
    $tmplHeaders->getHTMLFooter();
?>