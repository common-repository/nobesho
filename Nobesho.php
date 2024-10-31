<?php
/*
 * Plugin Name:       Nobesho
 * Plugin URI:        https://nobesho.com/plugin
 * Description:       با نوبشو میتوانید تمامی محصولاتتان را روزانه به صدها هزار نفر نمایش دهید!
 * Version:           1.0
 * Author:            Nobesho
 * Author URI:        https://nobesho.com
 * WP tested up to:   5.7.2
 * Requires PHP:      7.1
 * License:           GPLv3
 */
defined('ABSPATH') || exit;
define("NOBESHO_SITE_WEBHOOK", "https://api.nobesho.com/v1/webhook/");
define("NOBESHO_PLUGIN_URL", plugin_dir_url(__FILE__));
require_once 'include/vendor/autoload.php';
require_once 'include/Nobesho-class.php';
new Nobesho_Store();