<?php
/**
 * Divi Auto Excerpt
 *
 * @package		divi-auto-excerpt
 * @author		Jerry Simmons <jerry@ferventsolutions.com>
 * @copyright	2017 Jerry Simmons
 * @license		GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:	Divi Auto Excerpt
 * Plugin URI:	https://ferventsolutions.com
 * Description:	Automatically Create Blog Post Excerpts From The First Text Module In A Post
 * Version:		1.0
 * Author:		Jerry Simmons <jerry@ferventsolutions.com>
 * Author URI:	https://ferventsolutions.com
 * Text Domain:	divi-auto-excerpt
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 **/

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Automatically Create Blog Post Excerpts From The First Text Module In A Post
 *
 * @uses wp_insert_post_data filter
 **/
function wj_auto_excerpt( $data, $post ) {

	if( 'post' != $data['post_type'] ) { return $data; }
	if( false === strpos( $data['post_content'], '[et_pb_text' ) ) { return $data; }
	if( !empty( $data['post_excerpt'] ) ) {
		if( false === strpos( $data['post_excerpt'], 'auto_excerpt' ) ) { return $data; }
	}

	$post_content = $data['post_content'];
	$post_excerpt = $data['post_excerpt'];

	/**
	 * Get Text From First Text Module In Post Content
	 **/
	$text_module_start = strpos( $post_content, '[et_pb_text' );
	$text_module_end = strpos( $post_content, ']', $text_module_start + 1 );
	$text_module_close = strpos( $post_content, '[/et_pb_text]', $text_module_end );
	$text = substr( $post_content, $text_module_end + 1, $text_module_close - $text_module_end - 1 );

	$data['post_excerpt'] = wp_trim_words( $text, 55, '...' ) . PHP_EOL . '<!--auto_excerpt-->';
	return $data;
}
add_filter( 'wp_insert_post_data', 'wj_auto_excerpt', 10, 2 );