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
                <img alt='Přidej se k nám' 
                     src='data/login_pic.png'
                     class='img-fluid rounded-4 '
                     style='max-height: 520px;'
                >
            </div>
            
            <!-- Login Form -->
            <div class='col-12 col-md-5'>
                <div class='card border-0 shadow rounded-4'>
                    <div class='card-body p-4 p-lg-5'>
                        <h2 class='fw-bold text-center mb-4'>Přihlášení</h2>
            
                        <form method='POST' action=''>
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

        </div>
        ";
    }

    echo $view;
?>

<?php
    $tmplHeaders->getHTMLFooter();
?>