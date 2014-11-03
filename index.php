<?php

/**
 * Plugin Name: Carpenter
 * Version: 1.0
 * Author: Apsis Labs
 * Author URI: www.apsis.io
 */

// Load Classes
require_once('post-type.cls.php');
require_once('taxonomy.cls.php');
require_once('carpenter.cls.php');

// Load Data
require_once('custom-fields.php');
require_once('post-type-data.php');

// Run
new Carpenter($postTypeData);