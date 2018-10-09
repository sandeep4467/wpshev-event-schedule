<?php
/**
 * Plugin Name: WP Schedule Event
 * Description: Add Schedule Event and Manage Events.
 * Plugin URI: https://upcodex.com/
 * Author: Sandeep Kumar
 * Author URI: https://upcodex.com/
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: text-domain
 * Domain Path: domain/path
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('WPSHEV_PLUGIN_FILE')) {
    define('WPSHEV_PLUGIN_FILE', __FILE__);
}
/*
    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! class_exists('WpScheduleEvent')) {
    include_once('core/includes/class-wpshev.php');
    $wpshev = new WpScheduleEvent();
}
