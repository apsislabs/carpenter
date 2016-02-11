<?php

/**
 * Plugin Name: Carpenter
 * Version: 1.2.5
 * Author: Apsis Labs
 * Author URI: www.apsis.io
 */

if ( ! class_exists('aps_carpenter') ) {
    class aps_carpenter {
        function __construct() {
            // Include Core Files
            include_once('core/api.php');
            include_once('core/post-type.cls.php');
            include_once('core/taxonomy.cls.php');
            include_once('core/carpenter.cls.php');
            include_once('core/updater.cls.php');

            if ( is_admin() ) {
                // Check for Update on GitHub
                new CarpenterUpdater( __FILE__, 'apsislabs', 'carpenter' );
            }
        }
    }

    new aps_carpenter();
}
