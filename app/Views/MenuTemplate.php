<?php
    global $tplData;
    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<div class="row align-items-center justify-content-center py-5 mt-5">
    <div class="col">
        <h1 class="mb-5"> Jídelní lístek </h1>
    </div>

    <?php
        $btn_view = "";

        if ($tplData["isLogged"] && $tplData["user"]["priority"] >= WEB_MODALS["newProduct"]["access_value"]) {
            $btn_view .= "
            <div class='col-auto'> 
                <button type='button' class='btn btn-dark shadow-sm mb-5' data-bs-toggle='modal' data-bs-target='#newProduct'>
                    <i class='bi bi-pencil-square me-1'></i> Přidat produkt
                </button>
            
                <div class='modal fade' id='newProduct' tabindex='-1' aria-labelledby='newProductLabel' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                        <div class='modal-content border-0 shadow-lg'>
                        
                            <!-- HLAVIČKA -->
                            <div class='modal-header bg-dark text-white'>
                                <h5 class='modal-title fw-bold text-center w-100' id='newProductLabel'>
                                    <i class='bi bi-star-fill text-warning me-2 '></i>Nový Produkt
                                </h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Zavřít'></button>
                            </div>
                            
                            <!-- FORMULÁŘ -->
                                <form action='' method='POST' enctype='multipart/form-data' class='p-2'>
                                    <input type='hidden' name='action' value='newProduct'>
                                    
                                    <div class='modal-body'>
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='newProduct_name' class='form-label fw-semibold'>Název produktu 🍽</label>
                                            <input type='text' class='form-control' id='newProduct_name' 
                                                name='newProduct_name' placeholder='Zadejte název produktu' required
                                            >
                                        </div>
                                        
                                        <!-- Picture -->
                                        <div class='mb-3'>
                                            <label for='newProduct_pic' class='form-label fw-semibold'>Obrázek 📸</label>
                                            <input type='file' id='newProduct_pic' class='form-control' name='newProduct_pic' accept='image/png, image/gif, image/jpeg' required>
                                            <div class='form-text'>Podporované formáty: PNG, GIF, JPG.</div>
                                        </div>
                                        
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='newProduct_price' class='form-label fw-semibold'>Cena 💰</label>
                                            <input  type='number' min='0' step='0.01' class='form-control' id='newProduct_price' 
                                                    name='newProduct_price' placeholder='Zadejte cenu v Kč' required
                                            >
                                        </div>
                                        
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='newProduct_category' class='form-label fw-semibold'>Typ produktu 🏷️</label>
                                            <select class='form-select' id='newProduct_category' name='newProduct_category' required>
                                                <option value='' selected disabled>Vyberte typ produktu</option>";
                                            foreach ($tplData["categories"] as $category) {
                                                $btn_view .= "<option value='{$category["id_category"]}'>{$category["name"]}</option>";
                                            }
            $btn_view .= "
                                            </select>
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

    <?php
    $view = "";
    foreach ($tplData["menu"] as $category) {
        if (count($category[1]) > 0) {
            // Category Card Header
            $view .= "
        <section class='myBox-section card border-0 shadow-sm mb-5 w-100'>
          <div class='card-header bg-light'>
            <h2 class='mt-2 mb-2'>$category[0]</h2>
          </div>
          <div class='card-body p-0'>
            <ul class='list-group list-group-flush'>
        ";

            // Category's product
            foreach ($category[1] as $product) {
                // Rating
                $rating = floatval($tplData[htmlspecialchars($product["id_product"])."_rating"]);
                $stars = $tmplHeaders->setRatingSyle($rating);

                // Product View
                $view .= "
              <li class='list-group-item'>
                <div class='row align-items-center g-3'>
                  <div class='col-auto text-center'>
                    <img
                      src='{$product['photo_url']}'
                      alt='{$product["name"]}_photo'
                      class='img-fluid rounded'
                      width='160' height='100'
                    >
                  </div>

                  <div class='col'>
                    <h5 class='mb-1'>".$product["name"]."</h5>
                    <ul class='list-unstyled text-muted small mb-0'>
                      <li><strong>Cena:</strong> ".$product["price"]." Kč</li>
                      <li><strong>Hodnocení:</strong> ".$stars."</li>
                    </ul>
                  </div>
                </div>
              </li>
          ";
            }

            // Ending
            $view .= "
            </ul>
          </div>
        </section>
        ";
        }
    }

    echo $view;
    ?>
</div>
<?php
    $tmplHeaders->getHTMLFooter();
?>
