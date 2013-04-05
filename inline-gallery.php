<?php
/*
Plugin Name: inline-gallery
Plugin URI: http://nino-net.org
Description: Custom WordPress Gallery Plugin.
Version: 1.0
Author: Nino Mueller
Author URI: http://nino-net.org
*/


remove_shortcode('gallery');
add_shortcode('gallery', 'parse_gallery_shortcode');

function parse_gallery_shortcode($atts) {
 
	global $post;
 
	extract(shortcode_atts(array(
		'orderby' => 'menu_order ASC, ID ASC',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'full',
		'link' => 'file'
	), $atts));
 
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $id,
		'numberposts' => -1,
		'orderby' => $orderby
		); 
	$images = get_posts($args);

	$render = '';
	if (!is_single() ) {
	    $render.=renderInlineGallery($images);
	}
	else {
	    $render.=renderBlockGallery($images);
    	    return $render;
	}
 	
	
}

function renderBlockGallery($images) {

    $render = '';

    foreach( $images as $image) {
        $caption = $image->post_excerpt;
 
	$description = $image->post_content;
	if($description == '') $description = $title;
 
        $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
 
	$img = wp_get_attachment_image_src($image->ID, $size);


	$render.= '<img width="600px" height="310px" src="' . $img[0] . '" class="gallery-image" alt="' . $image_alt . '" /><br />';
    }

    return $render;
}

function renderInlineGallery($images) {
    
    global $post;

    $render = '';

    $render.= '<div class="slideshow" id="slideshow-' . time() . rand() . '">';
    $render.= '<a href="' . get_permalink($post->ID) . '">';
    $render.= '<ul class="slides">';

    foreach ( $images as $image ) {		
	$caption = $image->post_excerpt;
 
	$description = $image->post_content;
	if($description == '') $description = $title;
 
        $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
 
	$img = wp_get_attachment_image_src($image->ID, $size);


	$render.= '<li><img width="720px" height="540px" src="' . $img[0] . '" class="gallery-image" alt="' . $image_alt . '" /></li>';
    }

    $render.= '</ul>';
    $render.= '</a>';

    $render.= '<span class="arrow previous"></span>';
    $render.= '<span class="arrow next"></span>';
    $render.= '</div>';

    $render.= '<div class="mini-overview">';
    $render.= '<ul class="mini-slides">';
    $active = ' active';

      foreach ( $images as $image ) {		
	$caption = $image->post_excerpt;
 
	$description = $image->post_content;
	if($description == '') $description = $title;
 
        $image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
 
	$img = wp_get_attachment_image_src($image->ID, $size);


	$render.= '<li  class="mini-li' . $active . '"><img src="' . $img[0] . '" class="mini-image" alt="' . $image_alt . '" /></li>';

	$active = "";
    }
    $render.= '</ul>';

    $render.= '</div>';


    echo $render;
}



?>
