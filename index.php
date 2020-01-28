<?php
/*
Plugin Name: Xyoos Migration
Description: un plugin pour facilité la migration du site Xyoos
Author: Zoé Poullenot
Version: 0.1
*/

namespace XyoosMigration;

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-convert.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-migration.php';


// Launch class
(new Main)->register_hooks();
