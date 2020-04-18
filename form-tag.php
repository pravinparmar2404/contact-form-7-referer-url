<?php

add_action( 'wpcf7_init', 'adit_add_form_tag' );

function adit_add_form_tag() {
	wpcf7_add_form_tag(
		'referer-page',
		'adit_form_tag_handler', true);
}

function adit_form_tag_handler ( $tag ){
    
    $tag = new WPCF7_FormTag( $tag );

	if ( empty( $tag->name ) )
		return '';
		
	
    adit_rfr_add_static_files();
    
	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type, 'cf7-rfr' );

	if ( $validation_error )
		$class .= ' wpcf7-not-valid';

	$atts = array();
	
	$atts['class'] = $tag->get_class_option( $class );
	$atts['id'] = $tag->get_id_option();
	if ( $tag->has_option( 'readonly' ) ):
		$atts['readonly'] = 'readonly';
	endif;

	$value = (string) reset( $tag->values );

	$value = $tag->get_default_option( $value );
	$value = wpcf7_get_hangover( $tag->name, $value );

	$atts['value'] = $value;
	$atts['type'] = 'hidden';
	$atts['name'] = $tag->name;

	$atts = wpcf7_format_atts( $atts );

	$html = sprintf('<input %1$s />', $atts );

	return $html;
}

function adit_rfr_add_static_files(){
	$extension='.min.js';
	if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
		$extension='.js';
	}
  
    wp_enqueue_script( 'cf7-rfr-js', plugins_url( 'script'.$extension, __FILE__ ), array('jquery'), '1.0.0', true);
}

?>