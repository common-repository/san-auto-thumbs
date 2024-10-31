<?php 
/*
Plugin Name: San Auto Thumbs
Plugin URI: http://www.w3cgallery.com/w3c-blog/wordpress-plugins/san-auto-thumbs-wp-plugins
Description: Create all posts thumbs automatically if has any image in that post. It creates a tab "San Auto Thumbs" in "Settings" or "Options" tab
Version: 1.0
Author: SAN - w3cgallery.com & Windowshostingpoint.com
Author URI: http://www.w3cgallery.com/
*/

/*  Copyright 2008  SAN - w3cgallery.com & Windowshostingpoint.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; 
*/
/* Define Constants and variables*/

define('PLUGIN_URI_SAN_AUTO_THUMB', get_option('siteurl').'/wp-content/plugins/san-auto-thumbs/');

global $post, $wpdb, $san_auto_settings;

 $auto_thumb_settings = get_option('auto_thumb_settings');
// posts_id from database

if($auto_thumb_settings['posts_mode']=='Yes') { 

// Main function to diplay on front end
add_filter('the_content', 'SAN_Auto_Thumbs');

function SAN_Auto_Thumbs($content = false) {


// If is the home page, an archive, or search results
	if(is_front_page() || is_archive() || is_search()) :
#### SAN AUTO THUMBS	
global $post, $wpdb, $san_auto_settings;
	
$auto_thumb_settings = get_option('auto_thumb_settings');
$auto_posts_image_width = $auto_thumb_settings['posts_image_size'];
$auto_posts_image_height = $auto_thumb_settings['posts_image_size1'];
$auto_posts_content = $auto_thumb_settings['posts_content_size'];

if($auto_thumb_settings[posts_image_effect]!='left'){
$contentFloat = "left";
}else{
$contentFloat = "right";
}	
###########	

	
		$content = $post->post_excerpt;

	// If an excerpt is set in the Optional Excerpt box
		if($content) :
			$content = apply_filters('the_excerpt', $content);

	// If no excerpt is set
		else :
			$content = $post->post_content;
			### SAN
			$searchImg = '/<img.*?>/i';
			$imageYes = preg_match ($searchImg, $content, $image);
			
			$searchAll = '/<img.*?>|<object.*?>|<embed.*?>/i';
			$content = preg_replace ($searchAll,'',$content);
			####
			$excerpt_length = $auto_thumb_settings['posts_length'];
			$words = explode(' ', $content, $excerpt_length + 1);
			if(count($words) > $excerpt_length) :
				array_pop($words);
				array_push($words, '...');
				$content = strip_tags(implode(' ', $words));
			endif;	
		
#### setting
preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i',$image[0], $imagePath);
		 $finalImage = $imagePath[1][0];
		 
		 	$handle = @fopen($finalImage,'r');
				
			if($imageYes!=0 && $handle !== false) {
			 
			$content = '<div style="float:'.$auto_thumb_settings[posts_image_effect].';width:'.$auto_posts_image_width.'px;margin-'.$contentFloat.':5px;border:1px solid #ccc;padding:5px;"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><img class="thumb" src="'.PLUGIN_URI_SAN_AUTO_THUMB.'SANthumb.php?src='.$finalImage.'&amp;h='.$auto_posts_image_height.'&amp;w='.$auto_posts_image_width.'&amp;zc=1"/></a></div>
			<div style="float:'.$contentFloat.';width:'.$auto_posts_content.'px;">' . $content . '</div><div style="clear:both"></div>';
			}else{
			$content = '<div style="clear:both">' . $content . '</div>';
			}
echo '<div style="clear:both"></div>';
		endif;
	endif;

// Make sure to return the content
	return $content;

}
} ### if end


$data = array(
				'posts_mode' 		=> '',
				'posts_length' 		=> '',
				'posts_image_size'   => '',
				'posts_image_size1'   => '',
				'posts_image_effect' => '',
				'posts_content_size'   => '',
							);
$ol_flash = '';

global $post, $wpdb, $san_auto_settings;


// ADMIN PANLE SEETTING
function posts_auto_thumbs() {
    // Add new menu in Setting or Options tab:
    add_options_page('San Auto Thumbs', 'San Auto Thumbs', 8, 'postsoptions1', 'san_auto_thumbs_option');
}

/* Functions */

function san_auto_thumbs_option() {
global $ol_flash, $auto_thumb_settings, $_POST, $wp_rewrite;


if (isset($_POST['posts_mode'])) { 
	$auto_thumb_settings['posts_mode'] = $_POST['posts_mode'];
	update_option('auto_thumb_settings',$auto_thumb_settings);

		}
if (isset($_POST['posts_length'])) { 
	$auto_thumb_settings['posts_length'] = $_POST['posts_length'];
	update_option('auto_thumb_settings',$auto_thumb_settings);

		}

if (isset($_POST['posts_image_size'])) {
  $auto_thumb_settings['posts_image_size'] = $_POST['posts_image_size'];
  update_option('auto_thumb_settings',$auto_thumb_settings);

    }

if (isset($_POST['posts_image_size1'])) {
  $auto_thumb_settings['posts_image_size1'] = $_POST['posts_image_size1'];
  update_option('auto_thumb_settings',$auto_thumb_settings);

    }
	
if (isset($_POST['posts_image_effect'])) {
  $auto_thumb_settings['posts_image_effect'] = $_POST['posts_image_effect'];
  update_option('auto_thumb_settings',$auto_thumb_settings);

    }		

if (isset($_POST['posts_content_size'])) {
  $auto_thumb_settings['posts_content_size'] = $_POST['posts_content_size'];
  update_option('auto_thumb_settings',$auto_thumb_settings);

    }	

?>
     <?php If (!Empty($_POST)) : ?>
          <div id="message" class="updated fade"><p><strong><?php _e('Your settings have been saved.') ?></strong></p></div>
        <?php EndIf; ?>
<?php
echo '<div class="wrap">';

		echo '<h2>San Auto Thumbs Setting</h2>';
		echo '<table class="form-table"><form action="" method="post">
		<tr><td colspan="2"><p><strong>San Auto Thumbs</strong> gives your full freedom to show thumbs along with your post now.<br />
 NO NEED TO EDIT ANY THEME PAGE.just activate plugin and done! Only your first post image will be convert into thumbs.<br />
<font color="#FF0000" size="2">(* You need to add image in your post any where to make it works)</font></p></td></tr>

<tr><td width="200"><strong> Active Thumbs:</strong></td><td align="left">'; 
?>
	<select size="1" name="posts_mode">
		<option value="No" <?php Selected ($auto_thumb_settings['posts_mode'], 'No') ?>>No</option>
	<option value="Yes" <?php Selected ($auto_thumb_settings['posts_mode'], 'Yes') ?>>Yes</option>

	</select>
	<tr><td width="200"><strong> Thumb Position :</strong></td><td align="left">
	<select size="1" name="posts_image_effect">
	<option value="left" <?php Selected ($auto_thumb_settings['posts_image_effect'], 'left') ?>>Left</option>
	<option value="right" <?php Selected ($auto_thumb_settings['posts_image_effect'], 'right') ?>>Right</option>
	</select>
	</td></tr>
	<?php 
	echo '</td></tr>
		

<tr><td><strong>Post Words Length:</strong></td><td align="left"><input type="text" name="posts_length" value="' . htmlentities($auto_thumb_settings['posts_length']) . '" size="10" /><br />
<font color="#FF0000" size="2">Only in Numbers format like : 50</font></td></tr>

	<tr><td colspan="2"><h2>Add your Thumbs Width and Height.</h2></td></tr>
		<tr><td><strong>Thumb Width :</strong></td><td align="left"><input type="text" name="posts_image_size" value="' . htmlentities($auto_thumb_settings['posts_image_size']) . '" size="20%" /> px</td></tr>
			<tr><td><strong>Thumb Height :</strong></td><td align="left"><input type="text" name="posts_image_size1" value="' . htmlentities($auto_thumb_settings['posts_image_size1']) . '" size="20%" /> px</td></tr>
	<tr><td colspan="2"><h2>Add your Content area width</h2></td></tr>			
		<tr><td><strong>Content Width :</strong></td><td align="left"><input type="text" name="posts_content_size" value="' . htmlentities($auto_thumb_settings['posts_content_size']) . '" size="20%" /> px</td></tr>
		</table>';
		
				
echo '<Div class="submit"><input type="submit" value="Save Your Setting" /></div>
<p>It works with all images you added even remote server. make sure <strong>GD Library</strong> installed on your server.  <a href="http://www.w3cgallery.com/w3c-blog/wordpress-plugins/san-auto-thumbs-wp-plugins">more details Click here</a>.
		</form>';
echo '<p><a href="http://www.w3cgallery.com/w3c-blog/wordpress-plugins/san-auto-thumbs-wp-plugins">for Instructions and help online Please visit.</a> <br/>
If you like this plugin, please leave your comments on <a href="http://www.w3cgallery.com/w3c-validate/w3c-blog">w3cgallery.com</a> & <a href="http://www.Windowshostingpoint.com">Windowshostingpoint.com</a></p>';
		echo '</div>';

}

add_action('admin_menu', 'posts_auto_thumbs');

?>