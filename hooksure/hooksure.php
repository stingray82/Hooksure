<?php
/*
Plugin Name: Hooksure
Description: Add webhooks to SureForms for free.
Version: 1.0
Author: Stingray82
*/

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Include admin and hook files
include_once plugin_dir_path(__FILE__) . 'inc/admin.php';
include_once plugin_dir_path(__FILE__) . 'inc/hook.php';

// Plugin activation hook
function hooksure_activate() {
    // Activation code here
}
register_activation_hook(__FILE__, 'hooksure_activate');

// Plugin deactivation hook
function hooksure_deactivate() {
    // Deactivation code here
}
register_deactivation_hook(__FILE__, 'hooksure_deactivate');
