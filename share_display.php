<?php 
/*
Core logic to display social share icons at the required positions. 
*/
ob_start();
require_once('share_admin_page.php');

function simple_share_init() {
	// DISABLED IN THE ADMIN PAGES
	if (is_admin()) {
		return;
	}

	//GET ARRAY OF STORED VALUES
	$option = simple_share_get_options_stored();

	if ($option['active_buttons']['twitter']==true) {
		wp_enqueue_script('simple_share_twitter', 'http://platform.twitter.com/widgets.js','','',$option['jsload']);
	}
	
	if ($option['active_buttons']['Google_plusone']==true) {
		wp_enqueue_script('simple_share_google', 'http://apis.google.com/js/plusone.js','','',$option['jsload']);
	}
	if ($option['active_buttons']['linkedin']==true) {
		wp_enqueue_script('simple_share_linkedin', 'http://platform.linkedin.com/in.js','','',$option['jsload']);
	}

	wp_enqueue_style('share_style', '/wp-content/plugins/simplest_facebook_twitter_googleplusone_sharing/share_style.css');
	
}    

function share_contents($content)
{
	return share_facebook($content,'content');
}

function share_excerpt($content)
{
	return share_facebook($content,'excerpt');
}

function share_facebook($content, $filter)
{
  global $single;
  
  $option = simple_share_get_options_stored();
  $custom_disable = get_post_custom_values('disable_social_share');
  
	if (is_home()){
        $output = simple_social_share('auto');
		     	return  $output . $content;
			//return  $output . $content . $output;
	}
	
	return $content;
}

// Function to manually display related posts.
function simple_add_social_share()
{
 $output = simple_social_share('manual');
 echo $output;
}



function simple_social_share($source)
{
	//GET ARRAY OF STORED VALUES
	$url=site_url();
	$option = simple_share_get_options_stored();
	
	$border ='';

		

$output = '<div id="leftcontainerBox">';

		$output = '<div id="leftcontainerBox" style="' .$border. $bkcolor. 'position:' .$option['float_position']. '; top:' .$option['bottom_space']. '; left:' .$option['left_space']. ';">';
		if ($option['active_buttons']['facebook_like']==true) {
		$output .= '<div style="float:left; width:75px;padding-right:5px; margin:4px 4px 4px 4px;height:30px;">
			<iframe src="http://www.facebook.com/plugins/like.php?href=' . urlencode($url) . '&amp;layout=button_count&amp;show_faces=false&amp;width=75&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width=75px; height:21px;" allowTransparency="true"></iframe></div>';
		}
		
		if ($option['active_buttons']['twitter']==true) {
		$data_count = ($option['twitter_count']) ? 'horizontal' : 'none';
		if ($option['twitter_id'] != ''){
		$output .= '
			<div style="float:left; width:75px;padding-right:0px; margin:4px 0px 0px 0px;height:30px;">
			<a href="http://twitter.com/share" class="twitter-share-button" data-url="'. $url .'"  data-text="'. wp_title("",true) . '" data-count="horizontal" data-via="'. $option['twitter_id'] . '">Tweet</a>
			</div>';
		} else {
		$output .= '
			<div style="float:left; width:95px; padding-right:0px; margin:4px 0px 0px 0px;height:30px;">
			<a href="http://twitter.com/share" class="twitter-share-button" data-url="'. $url .'"  data-text="'. wp_title("",true) . '" data-count="horizontal">Tweet</a>
			</div>';
		}
		}
		
		if ($option['active_buttons']['Google_plusone']==true) {
		$output .= '<div style="float:left; width:80px; padding-right:0px; margin:4px 0px 0px 9px; height:30px;">
			<g:plusone size="medium" href="' . $url . 'count="false"></g:plusone>
			</div>';
		}

		
		
		
$output .= '			
			</div><div style="clear:both"></div><div style="padding-bottom:4px;"></div>';
			
		return $output;
}

function simple_social_share_shortcode () {
	$output = simple_social_share('shortcode');
	echo $output;
}

function facebook_like_thumbnails()
{
global $posts;
$default = '';
$content = $posts[0]->post_content; // $posts is an array, fetch the first element
$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
if ( $output > 0 ) {
$thumb = $matches[1][0];
echo "\n\n<!-- Thumbnail for facebook like -->\n<link rel=\"image_src\" href=\"$thumb\" />\n\n";
}
else
$thumb = $default;
}
?>





