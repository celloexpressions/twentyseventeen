<?php
/**
 * Twenty Seventeen Theme Customizer.
 *
 * @package Twenty Seventeen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function twentyseventeen_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Footer Image
	$wp_customize->add_section( 'twentyseventeen_footer_settings', array(
		'title'	  => __( 'Footer Image', 'twentyseventeen' ),
		'panel'	  => 'twentyseventeen_options_panel',
		'default' => '',
	) );

	$wp_customize->add_setting('twentyseventeen_footer_image', array(
		'transport'			=> 'refresh',
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
		'twentyseventeen_footer_image', array(
		'label'		=> __( 'Footer Image', 'twentyseventeen' ),
		'section'	=> 'twentyseventeen_footer_settings',
		'description' => __( 'Add an image to be displayed at the bottom of the Front Page template, above the footer.', 'twentyseventeen' ),
	) ) );
}
add_action( 'customize_register', 'twentyseventeen_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function twentyseventeen_customize_preview_js() {
	wp_enqueue_script( 'twentyseventeen_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'twentyseventeen_customize_preview_js' );
