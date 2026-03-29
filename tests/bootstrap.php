<?php

// Define WordPress constant to pass the file security check
define('ABSPATH', __DIR__ . '/');

// Stub WordPress/ACF functions used at include-time
function add_action() {}
function add_filter() {}
function load_plugin_textdomain() {}

// Include the plugin file
require_once dirname(__DIR__) . '/wordpress-seo-ultimate.php';
