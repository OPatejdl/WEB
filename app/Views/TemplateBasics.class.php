<?php
    class TemplateBasics {

        /**
         * @param string $pageTitle
         * @return void
         */
        public function getHTMLHeader(string $pageTitle): void {
            global $tplData;
            ?>
            <!DOCTYPE html>
            <html lang="cs">
                <head>
                    <meta charset="utf-8">
                    <meta name="description" content="Semestrlani prace - WEB">
                    <meta name="author" content="opatejdl">
                    <title><?php echo $pageTitle?></title>

                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
                    <link rel="stylesheet" href="myStyles.css">

                </head>
                <body>
                <header class="bg-dark text-white sticky-top">
                    <nav class="navbar navbar-expand-lg navbar-dark py-3">
                        <div class="container align-items-center">

                            <!-- Restaurant logo and name -->
                            <a href="index.php" class="navbar-brand d-flex align-items-center text-decoration-none">
                                <img src="data/logo_res.png"
                                     alt="Logo"
                                     class="img-fluid rounded-circle shadow border border-2 border-warning me-2"
                                     style="height:60px;width:60px;object-fit:cover;">
                                <span class="fw-bold fs-4 text-light mb-0">U Dvou Piváků</span>
                            </a>

                            <!-- Toggle navigation in case of smaller view -->
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <!-- Main navigation -->
                            <div class="collapse navbar-collapse order-lg-2 flex-grow-1" id="mainNav">
                                <ul class="navbar-nav mx-auto my-3 my-lg-0">
                                <?php
                                    foreach(WEB_PAGES as $key => $page) {
                                        if ($key != 'newReview' && $key != "newProduct" && $key != "register" && $key != "login") {
                                            echo "<li class='nav-item'>
                                                    <a class='nav-link px-3 fw-semibold fs-5' href='index.php?page=$key'>$page[title]</a>
                                                    </li>";
                                        }
                                    }
                                ?>
                                </ul>

                                <!-- Login and Register button -->
                                <div class="d-flex ms-lg-3 gap-2">
                                <?php if ($tplData["isLogged"]) {?>
                                    <!-- TODO: Implement case when user is logged -->
                                <?php } else {?>
                                    <a type='button' class='btn btn-primary' href='#'>Login</a>
                                    <a type='button' class='btn btn-secondary' href='#'>Register</a>
                                <?php } ?>
                                </div>
                            </div>

                        </div>
                    </nav>
                </header>
                <main>
                    <div class="container">
            <?php
        }

        /**
         * Function defines HTML Footer
         * @return void
         */
        public function getHTMLFooter() {
            ?>
                    </div>
                </main>
                <footer>
                    <div class="container">
                        <?php echo "Footer of all pages" ?>
                    </div>
                </footer>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
                </body>
                </html>
            <?php
        }
    }
?>
