<?php
/*
Plugin Name: Pitch Pro
Description: The best tool for creating pitches
Version: 1.0.1
Author: Imagine Simplicity, Timothy Wood @codearachnid
Author URI: http://www.imaginesimplicity.com
Text Domain: pitchpro
Credits:
	* UI field framework by Titan Framework
	* Background patterns from subtlepatterns.com
License: GPLv3 or later

Copyright 2009-2016 by Pitch Pro, Imagine Simplicity and
additional contributors This program is free software; you can
redistribute it and/or modify it under the terms of the GNU
General Public License as published by the Free Software
Foundation; either version 3 of the License, or (at your option)
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// bootstrap pitchpro app
global $pitchpro;
global $pitchpro_app;
global $pitchpro_option;

define( 'PITCHPRO_PATH', trailingslashit( dirname( __FILE__ ) ) );

include_once( PITCHPRO_PATH . 'lib/loader.php' );

pitchpro_file_loader([
	'lib/helpers',
	'lib/woo-overrides',
	'lib/titan-framework-checker',
	'lib/bootstrap',
	'lib/shortcodes',
	'lib/startup',
	'lib/pre-get-posts',
	'lib/ajax'
]);

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page('PitchPro Settings');

}
