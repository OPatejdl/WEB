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

                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
                    <link rel="stylesheet" href="myStyles.css">

                </head>
                <body class="d-flex flex-column min-vh-100">
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
                                        if (!in_array($key, ['newReview', "newProduct", "register", "login"])) {
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
                                    <a type='button' class='btn btn-primary' href='index.php?page=login'>Moje Info</a>
                                    <a type='button' class='btn btn-secondary' href='index.php?page=register'>Register</a>
                                <?php } else {?>
                                    <a type='button' class='btn btn-primary' href='index.php?page=login'>Login</a>
                                    <a type='button' class='btn btn-secondary' href='index.php?page=register'>Register</a>
                                <?php } ?>
                                </div>
                            </div>

                        </div>
                    </nav>
                    <br>
                </header>
                <main class="flex-grow-1">
                    <div class="container">
            <?php
        }

        /**
         * Function defines HTML Footer
         */
        public function getHTMLFooter(): void {
            ?>
                    </div>
                </main>
                <footer class="bg-dark text-white border-top border-2 border-warning mt-auto">
                    <div class="container py-3">
                        <div class="row text-center text-md-start gy-4">
                            <!-- Opening time-->
                            <div class="col-md-4">
                                <h5 class="fw-bold text-warning mb-3">
                                    Otevírací doba
                                </h5>
                                <ul class="list-unstyled mb-2 ms-3">
                                    <li class="mb-1">Po–Čt: 14:00–23:00</li>
                                    <li class="mb-1">Pá–So: 14:00–01:00</li>
                                    <li>Ne: Zavřeno</li>
                                </ul>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-md-4">
                                <h5 class="fw-bold text-warning mb-3">
                                    Adresa a kontakt
                                </h5>
                                <div class="ms-3">
                                    <p class="mb-1">U Dvou Piváků</p>
                                    <p class="mb-1"> Pivovarská 12, 301 00 Plzeň</p>
                                    <p class="mb-1"> +420 123 456 789 </p>
                                    <p class="mb-0">
                                        <a href="mailto:opatejdl@students.zcu.cz" class="text-white text-decoration-none">opatejdl@students.zcu.cz</a>
                                    </p>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="col-md-4">
                                <h5 class="fw-bold text-warning mb-3">
                                    Sledujte nás
                                </h5>
                                <ul class="list-unstyled justify-content-center justify-content-md-start ms-3 mb-1">
                                    <li class="mb-1">
                                        <a href="https://github.com/OPatejdl/WEB" target="_blank" class="text-white text-decoration-none fs-6">
                                            GitHub
                                        </a>
                                    </li>
                                    <li class="mb-1">
                                        <a href="https://facebook.com" target="_blank" class="text-white text-decoration-none fs-6">
                                            Facebook
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://instagram.com" target="_blank" class="text-white text-decoration-none fs-6">
                                            Instagram
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Copyright -->
                        <div class="bg-gray  text-center py-3 mt-3 border-top border-warning">
                            <small class="text-secondary">
                                &copy; <?php echo date('Y'); ?> Ondřej Patejdl
                            </small>
                        </div>
                    </div>
                </footer>

                <!--Bootstrap connect-->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
                </body>
            </html>
            <?php
        }

        public function setRatingSyle(float $rating): string {
            $stars = "";

            if ($rating == null) {
                $stars = "<span class='text-muted fst-italic'>Neohodnoceno</span>";;
            } else {
                $maxStars = 5;
                for ($i = 1; $i <= $maxStars; $i++) {
                    if ($i <= floor($rating)) {
                        $stars .= '<i class="fa fa-star" style="color: gold;"></i>';
                    } elseif ($i - 0.5 <= $rating) {
                        $stars .= '<i class="fa fa-star-half-alt" style="color: gold;"></i>';
                    } else {
                        $stars .= '<i class="fa fa-star-o" style="color: gold;"></i>';
                    }
                }
                $stars .= " (".number_format($rating, 1)." / $maxStars)";
            }

            return $stars;
        }

    }
?>
