<?php
/**
 * @wordpress_plugin
 * Plugin Name:  TweensTalent Plugin
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

// debug TODO: remove debug from wp-config
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! session_id() ) {
    session_start();
}

ob_start();
define( 'PLUGIN_URL', plugin_dir_path( __FILE__ ) );

require_once( 'admin/config.php' );
require_once( 'admin/includes/mail/register.php' );

// Require Classes
require_once( 'admin/classes/TweensTalent.php' );
require_once( 'admin/classes/ExternalApi.php' );
require_once( 'admin/classes/EmailTemplate.php' );
require_once( 'admin/classes/Tabs.php' );
require_once( 'admin/classes/WP_Mail.php' );

// Require Pages
require_once( 'admin/includes/tt-app-page.php' );
require_once( 'admin/includes/tt-event-page.php' );
require_once( 'admin/includes/tt-event-email-page.php' );
require_once( 'admin/includes/tt-statistics-page.php' );

$tweensTalent = new TweensTalent();