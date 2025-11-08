<?php

class ModalsDef
{
    /**
     * Function gets modals template for creating or editing product
     *
     * @param string $Id modal's Id
     * @param string $actionType type of the action
     * @param string $label label for inputs
     * @param string $btnLabel label for button
     * @param bool $isRequired sets if picture is required or not
     * @param string $productId id of product (editing)
     * @param string $nameVal name of the product (editing)
     * @param string $picVal path to product's picture (editing)
     * @param string $priceVal product's value (editing)
     * @param string $categoryId category of product
     * @return string template of product modal
     */
    public function productModal(string $Id, string $actionType, string $label, string $btnLabel, bool $isRequired,
                                 string $productId="",string $nameVal="", string $picVal="",
                                 string $priceVal="", string $categoryId=""): string {
        global $tplData;
        $required = $isRequired ? "required" : "";
        $modal_view = "
            <div class='modal fade' id='$Id' tabindex='-1' aria-labelledby='{$Id}Label' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                        <div class='modal-content border-0 shadow-lg'>
                        
                            <!-- header of modal -->
                            <div class='modal-header bg-dark text-white'>
                                <h5 class='modal-title fw-bold text-center w-100' id='{$actionType}Label'>
                                    <i class='bi bi-star-fill text-warning me-2 '></i> $label
                                </h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Zavřít'></button>
                            </div>
                            
                            <!-- Form -->
                                <form action='' method='POST' enctype='multipart/form-data' class='p-2'>
                                    <input type='hidden' name='action' value='$actionType'>
                                    <input type='hidden' name='productId' value='$productId'>
                                    
                                    <div class='modal-body'>
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_name' class='form-label fw-semibold'>Název produktu 🍽</label>
                                            <input type='text' class='form-control' id='{$actionType}_name' 
                                                name='{$actionType}_name' placeholder='Zadejte název produktu' value='$nameVal' required>                                            
                                        </div>
                                        
                                        <!-- Picture -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_pic' class='form-label fw-semibold'>Obrázek 📸</label>
                                            <input type='file' id='{$actionType}_pic' class='form-control' name='{$actionType}_pic'
                                                    accept='image/png, image/gif, image/jpeg' $required>
                                            <div class='form-text'>Podporované formáty: PNG, GIF, JPG.</div>
                                        </div>";
        if ($picVal != "") {
                    $modal_view .= "<div class='mt-2'>
                <p class='fw-semibold mb-1'>Aktuální obrázek:</p>
                <img src='$picVal' alt='Aktuální obrázek' style='max-width: 200px; border-radius: 8px;'>
            </div>";
        }

                                        
        $modal_view.= "
                                        <!-- Price -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_price' class='form-label fw-semibold'>Cena 💰</label>
                                            <input  type='number' min='0' step='0.01' class='form-control' id='{$actionType}_price' 
                                                    name='{$actionType}_price' placeholder='Zadejte cenu v Kč' value='$priceVal'  required
                                            >
                                        </div>
                                        
                                        <!-- Category -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_category' class='form-label fw-semibold'>Typ produktu 🏷️</label>
                                            <select class='form-select' id='{$actionType}_category' name='{$actionType}_category' required>";
        // Categories types
        if ($categoryId == "") {
            $modal_view .= "<option value='' selected>Vyberte typ produktu</option>";
            foreach ($tplData["categories"] as $category) {
                $modal_view .= "<option value='{$category["id_category"]}'>{$category["name"]}</option>";
            }
        } else {
            foreach ($tplData["categories"] as $category) {
                if ($category["id_category"] == (int)$categoryId) {
                    $modal_view .= "<option value='{$category["id_category"]}' selected>{$category["name"]}</option>";
                } else {
                    $modal_view .= "<option value='{$category["id_category"]}'>{$category["name"]}</option>";
                }
            }
        }


        $modal_view .= "
                                            </select>
                                        </div>
                                        
                                    </div>
                                                                
                                    <!-- Modal Footer -->
                                    <div class='modal-footer border-0'>
                                        <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>
                                            <i class='bi bi-x-circle me-1'></i>Zavřít
                                        </button>
                                        <button type='submit' class='btn btn-primary'>
                                            <i class='bi bi-send-fill me-1'></i> $btnLabel
                                        </button>
                                    </div>
                                </form>
                        
                        </div>
                    </div>
                </div>
        ";

        return $modal_view;
    }

    /**
     * Function gets modal's template for creating or editing review
     *
     * @param string $Id id of modal
     * @param string $actionType action type
     * @param string $label label of the modal
     * @param string $btnLabel label of btn for submit
     * @param string $reviewId id of review (editing)
     * @param string $productId id of reviewed product (editing)
     * @param string $evaluation review's evaluation (editing)
     * @param string $descriptionVal review's description (editing)
     * @return string template of review modal
     */
    public function reviewModal(string $Id, string $actionType, string $label, string $btnLabel,
                                string $reviewId="", string $productId="", string $evaluation="", string $descriptionVal=""): string {
        global $tplData;

        $reviewModal = "
                <div class='modal fade' id='$Id' tabindex='-1' aria-labelledby='{$Id}Label' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                        <div class='modal-content border-0 shadow-lg'>
                            
                            
                            <div class='modal-header bg-dark text-white'>
                                <h5 class='modal-title fw-bold text-center w-100' id='{$actionType}Label'>
                                    <i class='bi bi-star-fill text-warning me-2 '></i>$label
                                </h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Zavřít'></button>
                            </div>
            
                            <!-- Add Review Form -->
                            <form action='' method='POST' class='p-2'>
                                <input type='hidden' name='action' value='$actionType'>
                                <input type='hidden' name='reviewId' value='$reviewId'>
                                <div class='modal-body'>
                                    
                                    <!-- Product -->
                                    <div class='mb-3'>
                                        <label for='productSelect' class='form-label fw-semibold'>🍽 Produkt</label>
                                        <select class='form-select' id='productSelect' name='{$actionType}_Product' required>
                                            ";
        if ($productId == "") {
            $reviewModal .= "<option value='' selected disabled>Vyberte položku…</option>";
            foreach ($tplData["products"] as $product) {
                $reviewModal .= "<option value='{$product["id_product"]}'>{$product["name"]}</option>";
            }
        } else {
            foreach ($tplData["products"] as $product) {
                if ($product["id_product"] == $productId) {
                    $reviewModal .= "<option value='{$product["id_product"]}' selected disabled>{$product["name"]}</option>";
                }
            }
        }


        $reviewModal .= "
                                        </select>
                                    </div>
                                    <!-- Evaluation -->
                                    <div class='mb-3'>
                                        <label for='ratingSelect' class='form-label fw-semibold'>⭐ Hodnocení</label>
                                        <select class='form-select' id='ratingSelect' name='{$actionType}_Rating' required>";
        if ($productId == "") {
            $reviewModal .=       "
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
                                        </select>";
        } else {
            for ($val = 0; $val <= 5; $val+=0.5) {
                if (floatval($evaluation) == $val) {
                    $reviewModal .= "<option value='$val' selected>$val</option>";
                } else {
                    $reviewModal .= "<option value='$val'>$val</option>";
                }
            }
            $reviewModal .= "</select>";
        }

        $reviewModal .=    " <div class='form-text'>0 = nejhorší, 5 = nejlepší.</div>
                                    </div>
            
                                    <!-- Description -->
                                    <div class='mb-3'>
                                        <label for='reviewText' class='form-label fw-semibold'>📝 Popis</label>
                                        <textarea class='form-control' id='reviewText' name='{$actionType}_Description' 
                                         rows='4' placeholder='Jak ti chutnalo? Co bys vyzdvihl?' required>$descriptionVal</textarea>
                                    </div>
            
                                </div>
            
                                
                                <div class='modal-footer border-0'>
                                    <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>
                                        <i class='bi bi-x-circle me-1'></i>Zavřít
                                    </button>
                                    <button type='submit' class='btn btn-primary'>
                                        <i class='bi bi-send-fill me-1'></i>$btnLabel
                                    </button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            ";

        return $reviewModal;
    }
}