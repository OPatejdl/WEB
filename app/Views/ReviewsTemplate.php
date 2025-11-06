<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();

    require_once("ModalsDef.class.php");
    $modalsDef = new ModalsDef();
?>

<?php
    // HEADER
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<!-- Info -->
<div class="row align-items-center justify-content-center py-5 mt-5">
    <div class="col">
        <h1 class="mb-0"> Recenze </h1>
    </div>
    <?php
        $btn_view = "";
        if ($tplData["isLogged"] && $tplData["user"]["priority"] >= WEB_MODALS["newReview"]["access_value"]) {
            $btn_view = "
            <div class='col-auto'> 
                <button type='button' class='btn btn-dark shadow-sm' data-bs-toggle='modal' data-bs-target='#newReview'>
                    <i class='bi bi-pencil-square me-1'></i> Napsat recenzi
                </button>";
            $btn_view .= $modalsDef->reviewModal("newReview", "newReview", "Nová recenze", "Přidat recenzi");
        }
        echo $btn_view;
    ?>
</div>

<!-- Reviews -->
<?php
    $reviews_view = "";

    foreach ($tplData["products"] as $product) {
        if (isset($tplData["reviews"][$product["name"]]) && $tplData["reviews"][$product["name"]] != null) {
            $public_count = 0;

            // Review header - Product Name
            $review_card = "
            <section class='myBox-section card border-0 shadow-sm mb-5 w-100'>
              <div class='card-header bg-light'>
                <h2 class='mt-2 mb-2'>".$product["name"]."</h2>
              </div>
              <div class='card-body p-0'>
                <ul class='list-group list-group-flush'>
            ";

            // Reviews of product
            foreach ($tplData["reviews"][$product["name"]] as $review) {
                if (!$tplData["isLogged"] || $tplData["user"]["priority"] <= $tplData["priorities"][ROLE_CONSUMER]) {
                    if ($review["publicity"] == 1) {
                        $stars = $tmplHeaders->setRatingSyle($review["rating"]);
                        $review_card .= "
                        <li class='list-group-item'>
                            <div class='d-flex flex-column'>
                                <h5 class='mb-1'>" . $review["user_name"] . "</h5>
                                <ul class='list-unstyled text-muted small mb-0'>
                                  <li> <i>" . $review["description"] . "</i></li>
                                  <li><strong>Hodnocení:</strong>" . $stars . "</li>
                                </ul>
                            </div>
                        </li>
                        ";
                        $public_count++;
                    }
                } else {
                    $public_count++;
                    $stars = $tmplHeaders->setRatingSyle($review["rating"]);
                    $review_card .= "
                        <li class='list-group-item'>
                            <div class='col'>
                                <h5 class='mb-1'>" . $review["user_name"] . "</h5>
                                <ul class='list-unstyled text-muted small mb-0'>
                                  <li> <i>" . $review["description"] . "</i></li>
                                  <li><strong>Hodnocení:</strong>" . $stars . "</li>
                                </ul>
                                
                                <form action='' method='POST' class='text-end mt-auto'>
                                    <input type='hidden' name='action' value='changePublicity'>
                                    <input type='hidden' name='public_current_publicity' value='{$review["publicity"]}'>
                                    <input type='hidden' name='public_Review_id' value='{$review["id_review"]}'>";
                    if ($review["publicity"] == 1) {
                        $review_card .= "<button type='submit' class='btn btn-outline-primary btn-sm'>
                                        <i class='bi bi-eye me-1'></i> Skrýt
                                    </button>";
                    } else {
                        $review_card .= "<button type='submit' class='btn btn-outline-success btn-sm'>
                                        <i class='bi bi-eye me-1'></i> Zveřejnit
                                    </button>";
                    }
                    $review_card .= "
                                </form>
                            </div>
                        </li>
                    ";
                }
            }

            if ($public_count > 0) {
                $reviews_view .= $review_card;
                $reviews_view .= "
                    </ul>
                  </div>
                </section>
                ";
            }
        }
    }

    echo $reviews_view;
?>



<?php
    // FOOTER
    $tmplHeaders->getHTMLFooter();
?>