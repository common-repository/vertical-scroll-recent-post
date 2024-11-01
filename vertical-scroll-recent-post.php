<?php
/*
Plugin Name: Vertical Scroll Recent Post
Plugin URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-post/
Description: Vertical Scroll Recent Post plugin scroll the recent post title in the widget, the post scroll from bottom to top vertically.
Author: Gopi Ramasamy
Author URI: http://www.gopiplus.com/work/2010/07/18/vertical-scroll-recent-post/
Version: 14.0
Tags: Vertical, scroll, recent, post, title, widget
vsrp means Vertical Scroll Recent Post
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once dirname( __FILE__ ) . '/class-vertical-scroll-recent-post-widget.php';
require_once dirname( __FILE__ ) . '/class-vertical-scroll-recent-post-installer.php';
require_once dirname( __FILE__ ) . '/class-vertical-scroll-recent-post-settings.php';

register_activation_hook( __FILE__ , array( 'Vsrp_Installer', 'activate' ) );
register_deactivation_hook( __FILE__ , array( 'Vsrp_Installer', 'deactivate' ) );
register_uninstall_hook( __FILE__ , array( 'Vsrp_Installer', 'unistall' ) );

function vsrp_textdomain() {
    load_plugin_textdomain( 'vertical-scroll-recent-post', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'vsrp_textdomain' );

if ( is_admin() ) {
    $my_settings_page = new Vsrp_Settings();
}

// Add script and style files
function vsrp_add_files() {
    wp_enqueue_script('jquery');
	wp_register_script( 'vsrp_js', vsrp_plugin_url( 'vertical-scroll-recent-post.js' ) );
    wp_register_style ( 'vsrp_css', vsrp_plugin_url( 'vertical-scroll-recent-post.css') );
}
add_action( 'wp_enqueue_scripts', 'vsrp_add_files' );
add_action( 'login_enqueue_scripts', 'vsrp_add_files' );
add_action( 'admin_enqueue_scripts', 'vsrp_add_files' );

// Feature: Shortcode for in pages/posts
function vsrp_shortcode( $atts ) {
    $attr = shortcode_atts( array(
        'class' => '',
        'vsrp_id' => '0'
    ), $atts );

    $instance = array( 'class' => $attr[ 'class' ], 'vsrp_id' => $attr[ 'vsrp_id' ] );
    return "<div class=\"{$instance[ 'class' ]}\">" . Vertical_Recent_Post_Display::vsrp( $instance ) . "</div>";
}
add_shortcode( 'vsrp', 'vsrp_shortcode' );

//This returns the plugin file URL.
function vsrp_plugin_url( $path = '' ) {
	$url = plugins_url( $path, __FILE__ );

	if ( is_ssl() and 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}
?>