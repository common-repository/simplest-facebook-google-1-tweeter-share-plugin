<?php
/*
The main admin page for this plugin. The logic for enabling the plguin & different user input is written here. 
*/

function share_admin_menu() {
	
add_options_page('Simple Facebook Twitter Google+1 Share', 'Simple Facebook Twitter Google+1 Share', 'administrator',
'simple_social_share', 'share_admin_page');
}

function share_admin_page() {

	$option_name = 'simple_share';
if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

$active_buttons = array(
		'facebook_like'=>'Facebook like',
		'twitter'=>'Twitter',
		'Google_plusone'=>'Google PlusOne'		
	);	

	
	$out = '';
	
	if( isset($_POST['simple_share_position'])) {
		$option = array();
		//print_r($_POST);
		
		$option['auto'] = (isset($_POST['simple_share_auto_display']) and $_POST['simple_share_auto_display']=='on') ? true : false;
//echo $option['auto'];
		foreach (array_keys($active_buttons) as $item) {
			$option['active_buttons'][$item] = (isset($_POST['simple_share_active_'.$item]) and $_POST['simple_share_active_'.$item]=='on') ? true : false;
		}	
		
		$option['jsload'] = (isset($_POST['simple_share_javascript_load']) and $_POST['simple_share_javascript_load']=='on') ? true : false;

		$option['twitter_id'] = esc_html($_POST['simple_share_twitter_id']);		
		//print_r($option);
		update_option('simple_share', $option);
		// Put a settings updated message on the screen
		$out .= '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
	}
	
	//GET ARRAY OF STORED VALUES
	$option = simple_share_get_options_stored();
	$auto =    ($option['auto']) ? 'checked="checked"' : '';
	$jsload =  ($option['jsload']) ? 'checked="checked"' : '';
	$out .= '
	<div class="wrap">

	<h2>'.__( 'Facebook and Twitter share buttons', 'menu-test' ).'</h2>
	<div id="poststuff" style="padding-top:10px; position:relative;">
		<div style="float:left; width:74%; padding-right:1%;">
	<form name="form1" method="post" action="">
	<div class="postbox">
	<h3>'.__("General options", 'menu-test' ).'</h3>
	<div class="inside">
	<table>
	<tr><td style="padding-bottom:20px;" valign="top">'.__("Auto Display", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">
		<input type="checkbox" name="simple_share_auto_display" '.$auto.' />
		<span class="description">'.__("Enable Auto display of Social Share buttons on Home Page", 'menu-test' ).'</span>
	</td></tr>
	
	<tr><td style="padding-bottom:20px;" valign="top">'.__("Code for Manual Display", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">
	<code>&lt;?php if(function_exists(&#39;simple_add_social_share&#39;)) simple_add_social_share(); ?&gt;</code>
	</td></tr>

	<tr><td valign="top" style="width:130px;">'.__("Active share buttons", 'menu-test' ).':</td>
	<td style="padding-bottom:30px;">';
	
	foreach ($active_buttons as $name => $text) {
		$checked = ($option['active_buttons'][$name]) ? 'checked="checked"' : '';
		$out .= '<div style="width:150px; float:left;">
				<input type="checkbox" name="simple_share_active_'.$name.'" '.$checked.' /> '
				. __($text, 'menu-test' ).' &nbsp;&nbsp;</div>';

	

			}

	$out .= '</td></tr>';
	
$out .='<tr><td style="padding-bottom:20px;" valign="top">'.__("Load Javascript in Footer", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">
		<input type="checkbox" name="simple_share_javascript_load" '.$jsload.' />
		<span class="description">'.__("(Recommended, else loaded in header)", 'menu-test' ).'</span>
	</td></tr>
	<tr><td style="padding-bottom:20px;" valign="top">'.__("Your Twitter ID", 'menu-test' ).':</td>
	<td style="padding-bottom:20px;">
	<input type="text" name="simple_share_twitter_id" value="'.$option['twitter_id'].'" size="30">  
		 <span class="description">'.__("Specify your twitter id without @", 'menu-test' ).'</span>
	</td></tr> 

	<tr><td valign="top" colspan="2">
	<p class="submit">
		<input type="submit" name="simple_share_position" class="button-primary" value="'.esc_attr('Save Changes').'" />
	</p>
	</td></tr>
	</form>
	</table>
	</div>
	</div>';
	echo $out; 
}


// PRIVATE FUNCTIONS

function simple_share_get_options_stored() {
	//GET ARRAY OF STORED VALUES
	$option = get_option('simple_share');
	 
	if ($option===false) {
		//OPTION NOT IN DATABASE, SO WE INSERT DEFAULT VALUES
		$option = simple_share_get_options_default();
		add_option('simple_share', $option);
	} 
	else if(!is_array($option)) {
		// Versions below 1.2.2 compatibility
		$option = json_decode($option, true);
	}

	// Versions below 1.5.1 compatibility
	if (!isset($option['auto'])) {
		$option['auto'] = true;
	}
	// Versions below 1.4.1 compatibility
	if (!isset($option['jsload'])) {
		$option['jsload'] = true;
	}

		
	return $option;
}

function simple_share_get_options_default () {
	$option = array();
	$option['auto'] = true;
	$option['active_buttons'] = array('facebook_like'=>true,'twitter'=>true,'Google_plusone'=>true);
	$option['jsload'] = true;
	return $option;
}
?>