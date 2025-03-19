<?php
/**
 * Plugin Name:       Hooksure
 * Tested up to:      6.7.2
 * Description:       Add webhooks to SureForms for free.
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Version:           1.15
 * Author:            reallyusefulplugins.com
 * Author URI:        https://reallyusefulplugins.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hooksure
 * Website:           https://reallyusefulplugins.com
 * */



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
