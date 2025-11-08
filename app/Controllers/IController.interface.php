<?php

    /**
     * Interface for all controllers
     */
    interface IController {
        /**
         * Function ensures print of certain page
         *
         * @param string $pageTitle title of the page
         * @return string HTML code of the page
         */
        public function show(string $pageTitle): string;
    }
