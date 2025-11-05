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
        <h1 class="mb-0"> Recenze </h1>
    </div>
    <?php
        $btn_view = "";
        if ($tplData["isLogged"] && $tplData["user"]["priority"] >= WEB_MODALS["newReview"]["access_value"]) {
            $btn_view = "
            <div class='col-auto'> 
                <button type='button' class='btn btn-dark shadow-sm' data-bs-toggle='modal' data-bs-target='#newReview'>
                    <i class='bi bi-pencil-square me-1'></i> Napsat recenzi
                </button>
            
                <div class='modal fade' id='newReview' tabindex='-1' aria-labelledby='newReviewLabel' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                        <div class='modal-content border-0 shadow-lg'>
                            
                            <!-- HLAVIČKA -->
                            <div class='modal-header bg-dark text-white'>
                                <h5 class='modal-title fw-bold text-center w-100' id='newReviewLabel'>
                                    <i class='bi bi-star-fill text-warning me-2 '></i>Nová recenze
                                </h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Zavřít'></button>
                            </div>
            
                            <!-- FORMULÁŘ -->
                            <form action='' method='POST' class='p-2'>
                                <input type='hidden' name='action' value='newReview'>
                                <div class='modal-body'>
                                    
                                    <!-- Produkt -->
                                    <div class='mb-3'>
                                        <label for='productSelect' class='form-label fw-semibold'>🍽 Produkt</label>
                                        <select class='form-select' id='productSelect' name='newReview_Product' required>
                                            <option value='' selected disabled>Vyberte položku…</option>";
            foreach ($tplData["products"] as $product) {
                $btn_view .= "<option value='{$product["id_product"]}'>{$product["name"]}</option>";
            }
            $btn_view .= "
                                        </select>
                                    </div>
            
                                    <!-- Hodnocení -->
                                    <div class='mb-3'>
                                        <label for='ratingSelect' class='form-label fw-semibold'>⭐ Hodnocení</label>
                                        <select class='form-select' id='ratingSelect' name='newReview_Rating' required>
                                            <option value='' selected disabled>Vyberte hodnocení…</option>
                                            <option value='0'>0.0</option>
                                            <option value='0.5'>0.5</option>
                                            <option value='1'>1.0</option>
                                            <option value='1.5'>1.5</option>
                                            <option value='2'>2.0</option>
                                            <option value='2.5'>2.5</option>
                                            <option value='3'>3.0</option>
                                            <option value='3.5'>3.5</option>
                                            <option value='4'>4.0</option>
                                            <option value='4.5'>4.5</option>
                                            <option value='5'>5.0</option>
                                        </select>
                                        <div class='form-text'>0 = nejhorší, 5 = nejlepší.</div>
                                    </div>
            
                                    <!-- Popis -->
                                    <div class='mb-3'>
                                        <label for='reviewText' class='form-label fw-semibold'>📝 Popis</label>
                                        <textarea class='form-control' id='reviewText' name='newReview_Description' 
                                         rows='4' placeholder='Jak ti chutnalo? Co bys vyzdvihl?' required></textarea>
                                    </div>
            
                                </div>
            
                                <!-- PATIČKA -->
                                <div class='modal-footer border-0'>
                                    <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>
                                        <i class='bi bi-x-circle me-1'></i>Zavřít
                                    </button>
                                    <button type='submit' class='btn btn-primary'>
                                        <i class='bi bi-send-fill me-1'></i>Odeslat recenzi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            ";
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

            $review_card = "
            <section class='myBox-section card border-0 shadow-sm mb-5 w-100'>
              <div class='card-header bg-light'>
                <h2 class='mt-2 mb-2'>".$product["name"]."</h2>
              </div>
              <div class='card-body p-0'>
                <ul class='list-group list-group-flush'>
            ";

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