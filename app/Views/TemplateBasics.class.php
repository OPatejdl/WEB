<?php
    class TemplateBasics {

        /**
         * @param string $pageTitle
         * @return void
         */
        public function getHTMLHeader(string $pageTitle): void
        {
            ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                    <meta name="description" content="Semestrlani prace - WEB">
                    <meta name="author" content="opatejdl">
                    <title><?php echo $pageTitle?></title>
                </head>
                <body>
                <header class="container">
                    <div>
                        <div>
                            <ul>
                            <?php
                                foreach(WEB_PAGES as $page) {
                                    echo "<li>$page[title]</li>";
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
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
                </body>
                </html>
            <?php
        }
    }
?>
