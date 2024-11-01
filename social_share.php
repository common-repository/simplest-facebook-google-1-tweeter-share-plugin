<?php
/*
Plugin Name: Simplest Facebook Twitter Google+1 Share
Description: No more fumbling around with your website's core HTML just to get a simple social media button to appear. This plugin is a quick and easy way to add social media buttons to your website, including:

-Facebook Like Button
-Tweet Button
-Google +1 Button

This plugin was developed and written by Vikash Saini.

Live Demo: <a href="http://www.emineer.com">Emineer</a>
Author: Ank Informatics
Author URI: http://www.ankinfo.com
Plugin URI: http://www.ankinfo.com/downloads/
Version: 1.0
License: GPL
*/
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

require_once('share_admin_page.php');
require_once('share_display.php');


if (!function_exists('is_admin')) 
{
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'share_twitter_facebook_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'share_twitter_facebook_remove' );

function share_twitter_facebook_install() 
{
/* Do Nothing */
}

function share_twitter_facebook_remove() {
/* Deletes the database field */
delete_option('simple_twitter_facebook_share');
}

if(is_admin())
{
add_action('admin_menu', 'share_admin_menu');
}
else
{
 add_action('init', 'simple_share_init');
 add_shortcode('simple_social_share', 'simple_social_share_shortcode' );
 add_action('wp_head', 'facebook_like_thumbnails');
 $option = simple_share_get_options_stored();
 if($option['auto'] == true)
 {
  add_filter('the_content', 'share_contents');
  add_filter('the_excerpt', 'share_excerpt');
 } 
}
?>