<?php
/*
Plugin Name: Contact Form 7 Referer URL
Plugin URI: https://adit.com/
Description: A simple contact form 7 in referer url generate plugin.
Version: 1.0
Author: Pravin Parmar
Author URI: https://adit.com
License: GPL
*/

error_reporting(0);

add_action( 'admin_init', 'adit_check_parent_plugin' );

function adit_check_parent_plugin() {

    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {

        add_action( 'admin_notices', 'adit_parent_not_active' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {

            unset( $_GET['activate'] );

        }

    }

}

function adit_parent_not_active(){

    ?>

	<div class="error">

		<p>Sorry, but <strong>Contact Form 7</strong> requires <strong><a href="wordpress.org/plugins/contact-form-7/">Contact Form 7</a></strong>.</p>

	</div>

	<?php

}

function adit_custom_styles()
{
    // Register the style like this for a plugin:
    wp_register_style( 'custom-style', plugins_url( '/custom-style.css', __FILE__ ), array(), '20120208', 'all' );
     
    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'custom-style' );
}
add_action( 'wp_enqueue_scripts', 'adit_custom_styles' );

/*
add_action( 'admin_init', 'getIP' );

function getIP() {

	$sProxy = '';
	if ( getenv( 'HTTP_CLIENT_IP' ) ) {
		$sProxy = $_SERVER['REMOTE_ADDR'];
		$sIP    = getenv( 'HTTP_CLIENT_IP' ) ;
	} elseif( $_SERVER['HTTP_X_FORWARDED_FOR'] ) {
		$sProxy = $_SERVER['REMOTE_ADDR'];
		$sIP    = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$sIP    = $_SERVER['REMOTE_ADDR'];
	}
	if ( ! empty( $sProxy ) ) {
		$sIP = $sIP . 'via-proxy:' . $sProxy;
	}
	return $sIP;
}

add_action( 'admin_init', 'setRefererTransient' );

function setRefererTransient( $uniqueID ) {
	if ( false === ( $void = get_transient( $uniqueID ) ) ) {
		// set a transient for 2 hours
		set_transient( $uniqueID, $_SERVER['HTTP_REFERER'], 60*60*24 );
	}
}

add_action( 'admin_init', 'getRefererPage' );

function getRefererPage( $form_tag ) {
	if ( $form_tag['name'] == 'referer-page' ) {
		$uniqueID = getIP();
		setRefererTransient( $uniqueID );	

		$site_url = site_url();
		$refefral_url =  $_POST[URL]; //wp_referer_field();// $_SERVER['HTTP_REFERER'];

		$form_tag['values'][] = $refefral_url;
		
		session_start();
		$_SESSION["refefral_url"] = $refefral_url;
		$refefral_url_session = $_SESSION["refefral_url"];
		session_register($refefral_url_session); 

		
		if(empty($_SERVER['HTTP_REFERER'])){

			$form_tag['value'][] = Direct;
		}
		elseif( strpos( $site_url, $refefral_url ) !== false){

   			$form_tag['value'][] = Organic;
		}
		else{
			$form_tag['value'][] = Adword;
		}
	}
	return $form_tag;
}
add_filter( 'wpcf7_form_tag', 'getRefererPage' );


function getIP() {
$sProxy = '';
if ( getenv( 'HTTP_CLIENT_IP' ) ) { 
$sProxy = $_SERVER['REMOTE_ADDR'];
$sIP = getenv( 'HTTP_CLIENT_IP' ) ; 
} elseif( $_SERVER['HTTP_X_FORWARDED_FOR'] ) { 
$sProxy = $_SERVER['REMOTE_ADDR'];
$sIP = $_SERVER['HTTP_X_FORWARDED_FOR']; 
} else { 
$sIP = $_SERVER['REMOTE_ADDR'];
}
if ( ! empty( $sProxy ) ) {
$sIP = $sIP . 'via-proxy:' . $sProxy; 
}
return $sIP;
}

function setRefererTransient( $uniqueID ) {
if ( false === ( $void = get_transient( $uniqueID ) ) ) {
// set a transient for 2 hours
set_transient( $uniqueID, $_SERVER['HTTP_REFERER'], 60*60*2 ); 
}
}
*/
function getRefererPage( $form_tag ) {	
if ( $form_tag['name'] == 'referer-page' ) {
$site_url = get_site_url();
$refefral_url = $_SERVER['HTTP_REFERER'];


 $refefral_url_new = preg_replace(".*([^\.]+)(com|net|org|info|coop|int|co\.uk|org\.uk|ac\.uk|uk|__and so on__)$", "", $refefral_url);

$cookie_name = "Referer";
$cookie_value = $refefral_url_new;
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

	//$form_tag['values'][] = $refefral_url_new;
	
	if(empty($_COOKIE[$cookie_name])){

		$form_tag['values'][] = Direct;
	}
	elseif( strcmp( "google", $_COOKIE[$cookie_name]))
	{
		$form_tag['values'][] = "Google Organic";
	}
	elseif( strcmp( "bing", $_COOKIE[$cookie_name]))
	{
		$form_tag['values'][] = "Bing Organic";
	}
	else
	{
		$form_tag['values'][] = Adword;
	}

}
return $form_tag;
}

if ( !is_admin() ) {
add_filter( 'wpcf7_form_tag', 'getRefererPage' );
}

?>