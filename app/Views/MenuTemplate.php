<?php
    global $tplData;
    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();

    require_once("ModalsDef.class.php");
    $modalsDef = new ModalsDef();
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
            
                {$modalsDef->productModal("newProduct", "newProduct", "Nový Produkt", "Přidat", true)}
                
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
                $stars = $tmplHeaders->setRatingStyle($rating);

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
                    </ul>";
                if ($tplData["isLogged"] && $tplData["user"]["priority"] >= $tplData["priorities"][ROLE_MANAGER]) {
                    $view .= "
                    <div class='d-flex gap-2 justify-content-end mt-auto'>
                        <button type='button' class='btn btn-outline-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editProduct{$product["id_product"]}'>
                            <i class='bi bi-pencil-square me-1'></i> Upravit produkt
                        </button>
                        
                        <form action='' method='POST' class='m-0 p-0'>
                            <input type='hidden' name='action' value='deleteProduct'>
                            <input type='hidden' name='del_productId' value='{$product["id_product"]}'>
                            <button type='submit' class='btn btn-outline-danger btn-sm'>
                                <i class='bi bi-x-circle me-1'></i> Smazat
                            </button>
                        </form>
                        
                        {$modalsDef->productModal("editProduct{$product['id_product']}", "editProduct", "Uprav Produkt", "Upravit", false,
                                                   $product["id_product"], $product["name"], $product["photo_url"], $product["price"], $product["fk_id_category"] )}
                    </div>                
                    ";
                }
                $view .= "
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
