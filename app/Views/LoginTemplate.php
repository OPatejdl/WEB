<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<?php
    $view = "";
    if (!$tplData["isLogged"]){
        $view .= "
        <div class='row align-items-center justify-content-center py-5'>
            <!-- Picture -->
            <div class='col-12 col-md-6 mb-4 mb-md-0 d-flex justify-content-center'>
                <img alt='Vítej zpět!' 
                     src='data/login_pic.png'
                     class='img-fluid rounded-4 '
                     style='height: auto;
                            object-fit: cover;
                            width: 100%;'
                >
            </div>
            
            <!-- Login Form -->
            <div class='col-12 col-md-5'>
                <div class='card border-0 shadow rounded-4'>
                    <div class='card-body p-4 p-lg-5'>
                        <h2 class='fw-bold text-center mb-4'>Přihlášení</h2>
        ";

        if ($tplData["error"] != "") {
            $view .= "
                    <div class='text-center text-danger fw-semibold mb-3'>
                        " . htmlspecialchars($tplData["error"]) . "
                    </div>
            ";
        }

        $view .= "
                <form method='POST' action=''>
                    <input type='hidden' name='action' value='login'>
    
                    <!-- Username -->
                    <div class='form-floating mb-3'>
                        <input type='text' class='form-control' id='login-username' name='username' required>
                        <label for='login-username'>Uživatelské jméno</label>
                    </div>
    
                    <!-- Password -->
                    <div class='form-floating mb-4'>
                        <input type='password' class='form-control' id='login-password' name='password' required minlength='6'>
                        <label for='login-password'>Heslo</label>
                    </div>
    
                    <!-- Submit -->
                    <div class='d-grid'>
                        <button type='submit' class='btn btn-dark btn-lg'>
                            Přihlásit se
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ";
    } else {

        $view .= "
        <div class='row py-5'>
            <div class='col-md-8 offset-md-2'>

            <!-- Uživatelské informace -->
            <div class='card mb-3 shadow-sm'>
                <div class='card-body'>
                    <h3 class='card-title fw-bold'>Profil uživatele</h3>
                    <ul class='list-group list-group-flush list-unstyled'>
                        <li><strong>Uživatelské jméno:</strong> " . $tplData['user']['username'] . "</li>
                        <li><strong>E-mail:</strong> " . $tplData['user']['email'] . "</li>
                        <li><strong>Role:</strong> " . $tplData['user']['role'] . "</li>
                    <form method='POST' action=''>
                        <input type='hidden' name='action' value='logout'>
                        <button type='submit' class='btn btn-danger mt-3'>Odhlásit se</button>
                    </form>
                </div>
            </div>
        </div>
        
        <hr class='my-4'>
        <h4 class='fw-bolder'>Moje recenze</h4>
        ";

        if (empty($tplData["user_reviews"])){
            $view .= "<p>Zatím žádné recenze</p>";
        } else {
            foreach ($tplData["user_reviews"] as $productName => $productReviews) {
                $view .= "
                <section class='myBox-section card border-0 shadow-sm mb-5 w-100'>
                  <div class='card-header bg-light'>
                    <h2 class='mt-2 mb-2'>".$productName."</h2>
                  </div>
                  
                  <div class='card-body p-0'>
                    <ul class='list-group list-group-flush'>
                ";

                foreach ($productReviews as $productReview) {
                    $stars = $tmplHeaders->setRatingSyle($productReview["rating"]);
                    $view .= "
                    <li class='list-group-item'>
                        <div class='col'>
                            <ul class='list-unstyled text-muted small mb-0'>
                              <li> <i>" . $productReview["description"] . "</i></li>
                              <li><strong>Hodnocení:</strong>" . $stars . "</li>
                            </ul>
                    </li>
                    ";
                }
                $view .= "
                    </ul>
                  </div>
                </section>";
            }
        }
    }

    echo $view;
?>

<?php
    $tmplHeaders->getHTMLFooter();
?>