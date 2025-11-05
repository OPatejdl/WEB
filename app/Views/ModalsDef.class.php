<?php

class ModalsDef
{
    public function productModal(string $Id, string $actionType, string $label, $btnLabel,
                                 string $productId="",string $nameVal="", string $picVal="",
                                 string $priceVal="", string $categoryId=""): string {
        global $tplData;
        $modal_view = "
            <div class='modal fade' id='$Id' tabindex='-1' aria-labelledby='{$Id}Label' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
                        <div class='modal-content border-0 shadow-lg'>
                        
                            <!-- HLAVIČKA -->
                            <div class='modal-header bg-dark text-white'>
                                <h5 class='modal-title fw-bold text-center w-100' id='{$actionType}Label'>
                                    <i class='bi bi-star-fill text-warning me-2 '></i> $label
                                </h5>
                                <button type='button' class='btn-close btn-close-white' data-bs-dismiss='modal' aria-label='Zavřít'></button>
                            </div>
                            
                            <!-- FORMULÁŘ -->
                                <form action='' method='POST' enctype='multipart/form-data' class='p-2'>
                                    <input type='hidden' name='action' value='$actionType'>
                                    <input type='hidden' name='productId' value='$productId'>
                                    
                                    <div class='modal-body'>
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_name' class='form-label fw-semibold'>Název produktu 🍽</label>
                                            <input type='text' class='form-control' id='{$actionType}_name' 
                                                name='{$actionType}_name' placeholder='Zadejte název produktu' value='$nameVal' required
                                            >
                                        </div>
                                        
                                        <!-- Picture -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_pic' class='form-label fw-semibold'>Obrázek 📸</label>
                                            <input type='file' id='{$actionType}_pic' class='form-control' name='{$actionType}_pic'
                                                    accept='image/png, image/gif, image/jpeg' required>
                                            <div class='form-text'>Podporované formáty: PNG, GIF, JPG.</div>
                                        </div>";
        if ($picVal != "") {
                    $modal_view .= "<div class='mt-2'>
                <p class='fw-semibold mb-1'>Aktuální obrázek:</p>
                <img src='$picVal' alt='Aktuální obrázek' style='max-width: 200px; border-radius: 8px;'>
            </div>";
        }

                                        
        $modal_view.= "
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_price' class='form-label fw-semibold'>Cena 💰</label>
                                            <input  type='number' min='0' step='0.01' class='form-control' id='{$actionType}_price' 
                                                    name='{$actionType}_price' placeholder='Zadejte cenu v Kč' value='$priceVal'  required
                                            >
                                        </div>
                                        
                                        <!-- Name -->
                                        <div class='mb-3'>
                                            <label for='{$actionType}_category' class='form-label fw-semibold'>Typ produktu 🏷️</label>
                                            <select class='form-select' id='{$actionType}_category' name='{$actionType}_category' required>";
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
                                                                
                                    <!-- PATIČKA -->
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
}