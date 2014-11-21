<?php

/**
 * Plugin Name: Carpenter
 * Version: 1.0.2
 * Author: Apsis Labs
 * Author URI: www.apsis.io
 */

if ( ! class_exists('aps_carpenter') ) {
    class aps_carpenter {

        function __construct() {
            add_action('init', array($this, 'init'), 1);
        }

        function init() {
            $this->loadFiles();
        }

        private function loadFiles() {
            // Load Core
            require_once('core/api.php');
            require_once('core/post-type.cls.php');
            require_once('core/taxonomy.cls.php');
            require_once('core/carpenter.cls.php');

            // Load Data
            require_once('custom-fields.php');
            require_once('post-type-data.php');
        }
    }

    new aps_carpenter();
}