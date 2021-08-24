<?php

/*
Plugin Name: Brizy Custom Styles
Plugin URI: http://wordpress.org/
Description: The missing style editor for brizy pagebuilder
Author: mcguffin
Version: 0.0.1
Author URI: https://github.com/mcguffin
License: GPL3
GitHub Plugin URI: https://github.com/mcguffin/brizy-custom-styles
Requires WP: 4.8
Requires PHP: 5.6
Text Domain: brizy-custom-styles
Domain Path: /languages/
*/

/*  Copyright 2021 mcguffin

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

/*
Plugin was generated with Jörn Lund's WP Skelton
https://github.com/mcguffin/wp-skeleton
*/


namespace BrizyCustomStyles;

if ( ! defined('ABSPATH') ) {
	die('FU!');
}


require_once __DIR__ . DIRECTORY_SEPARATOR . 'include/autoload.php';

Core\Core::instance( __FILE__ );

if ( is_admin() || defined( 'DOING_AJAX' ) ) {
}
