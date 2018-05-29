<?php
/**
 * Plugin Name:  Tweenstalent Plugin
 * Plugin URI:   https://#
 * Description:  Dit is een custom plugin speciaal gemaakt voor tweenstalent. Het zorgd ervoor dat je de App en het Evenement kan aanpassen. Ook kan je de statistieken bekijken van het Evenement
 * Version:      0.0.1
 * Author:       M
 * Author URI:   https://#
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *
 * {Plugin Name} is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * {Plugin Name} is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with {Plugin Name}. If not, see {URI to Plugin License}.
 */

///plugin-name
//     plugin-name.php
//     uninstall.php
//               /languages
//               /includes
//               /admin
//               /js
//               /css
//               /images
//               /public
//          /js
//           /css
//           /images

// debug TODO: remove later
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
define( 'SCRIPT_DEBUG', true );

// Api options
require_once( 'ExternalApi.php' );

// Admin Panel
require_once( 'admin/eventPages.php' );
require_once( 'admin/appPages.php' );
require_once( 'admin/admin-panel.php' );

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// include Classes
include_once 'admin/Classes/Tabs.php';

add_action( 'admin_menu', 'tt_admin_menu' );

function load_scripts($hook)
{
    if ( strpos( $hook, 'tt-events' ) !== false ) {
        // moment plugin
        wp_register_script( 'moment-script', plugins_url( '/admin/Libraries/MomentJs/moment-with-locales.min.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'moment-script' );

        // datatables
        wp_register_script( 'dt-script', plugins_url( '/admin/Libraries/Datatables/datatables.js', __FILE__ ), array('jquery'), null, true );
        wp_enqueue_script( 'dt-script' );

        // custom dt
        wp_register_script( 'dt-custom', plugins_url( '/admin/js/datatables.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dt-custom' );

        // datetime picker plugin
        wp_register_script( 'dtp-script', plugins_url( '/admin/Libraries/DatetimePicker/jquery.datetimepicker.full.min.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dtp-script' );

        // custom script for datetime picker
        wp_register_script( 'dtp-custom', plugins_url( '/admin/js/datetime.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'dtp-custom' );

        // custom script for address validation
        wp_register_script( 'av', plugins_url( '/admin/js/addressValidation.js', __FILE__ ), array( 'jquery' ), null, true );
        wp_enqueue_script( 'av' );
    }
}


function load_styles($hook)
{
    // DatetimePicker plugin
    wp_register_style( 'dtp-style', plugins_url( '/admin/Libraries/DatetimePicker/jquery.datetimepicker.min.css', __FILE__ ), array(), null, 'all' );
    wp_enqueue_style( 'dtp-style' );

    // DataTables
    wp_register_style('bootstrap', plugins_url( '/admin/Libraries/DataTables/datatables.css', __FILE__));
    wp_enqueue_style('bootstrap');

    // DataTables
    wp_register_style('bootstrap', plugins_url( '/admin/Libraries/DataTables/datatables.css', __FILE__));
    wp_enqueue_style('bootstrap');
}

// add custom scripts and styles
add_action( 'admin_enqueue_scripts', 'load_scripts' );
add_action( 'admin_enqueue_scripts', 'load_styles' );