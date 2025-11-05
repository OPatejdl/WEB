<?php
    global $tplData;

    require_once("TemplateBasics.class.php");
    $tmplHeaders = new TemplateBasics();
?>

<?php
    $tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<?php
    $view = "
        <div class='row align-items-center justify-content-center py-5 mt-auto'>
            <!-- Picture -->
            <div class='col-12 col-md-6 d-flex justify-content-center'>
                <img alt='Přidej se k nám' 
                     src='data/register_pic.png'
                     class='img-fluid rounded-4'
                     style='height: auto;
                            object-fit: cover;
                            width: 100%;'
                >
            </div>
            
            <!-- Register form-->
            <div class='col-12 col-md-6'>
                <div class='card border-0 shadow rounded-4'>
                    <div class='card-body p-4 p-lg-5'>
                        <h2 class='fw-bold text-center mb-4'>Registrace</h2>
            ";

    if ($tplData["error"] != "") {
        $view .= "
                        <div class='text-center text-danger fw-semibold mb-3'>
                            " . htmlspecialchars($tplData["error"]) . "
                        </div>
                ";
    }

    $view .="           
                        <form method='POST' action=''>
                            <input type='hidden' name='action' value='register'>
        
                            <!-- Username -->
                            <div class='form-floating mb-3'>
                                <input  type='text' class='form-control' id='register-username' name='username' 
                                        value='{$tplData["username"]}' required>
                                <label for='register-username'>Uživatelské jméno</label>
                            </div>
                            
                            <!-- Email -->
                            <div class='form-floating mb-3'>
                                <input  type='email' class='form-control' id='register-email' name='email' 
                                        value='{$tplData["email"]}' required>
                                <label for='register-email'>Email</label>
                            </div>
        
                            <!-- Password -->
                            <div class='form-floating mb-4'>
                                <input type='password' class='form-control' id='register-password' name='password' required minlength='6'>
                                <label for='register-password'>Heslo</label>
                            </div>
                            
                            <!-- Repeat Password -->
                            <div class='form-floating mb-4'>
                                <input type='password' class='form-control' id='register-password-repeat' name='password_repeat' required minlength='6'>
                                <label for='register-password_repeat'>Heslo Podruhé</label>
                            </div>
        
                            <!-- Submit -->
                            <div class='d-grid'>
                                <button type='submit' class='btn btn-dark btn-lg'>
                                    Registrovat se
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    ";

    echo $view;
?>


<?php
    $tmplHeaders->getHTMLFooter();
?>