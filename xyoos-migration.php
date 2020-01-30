<?php
/*
Plugin Name: Xyoos Migration
Description: Migration des contenus vers Gutenberg
Author: Maxime BJ & Zoé Poullenot
Version: 1.0
*/

namespace XyoosMigration;

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'classes/class-convert.php';
require_once plugin_dir_path( __FILE__ ) . 'classes/class-migration.php';


// Launch class
(new Main)->register_hooks();
