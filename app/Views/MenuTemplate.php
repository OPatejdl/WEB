<?php
    global $tplData;
    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<div class="row align-items-center justify-content-center py-5 mt-5">
    <h1> Jídelní lístek </h1>
    <?php
    $view = "";
    foreach ($tplData["menu"] as $category) {
        if (count($category[1]) > 0) {
            // Category Card Header
            $view .= "
        <section class='menu-section card border-0 shadow-sm mb-5 w-100'>
          <div class='card-header bg-light'>
            <h2 class='mt-2 mb-2'>$category[0]</h2>
          </div>
          <div class='card-body p-0'>
            <ul class='list-group list-group-flush'>
        ";

            // Category's product
            foreach ($category[1] as $product) {
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
                    <ul class='list-inline text-muted small mb-0'>
                      <li class='list-inline-item'><strong>Cena:</strong> ".$product["price"]." Kč</li>
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
