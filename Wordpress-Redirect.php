<?php
/*
Plugin Name: Wordpress Redirect
Description: Redirect pages to other pages!
Version: 1.0
Author: Liam Parker
Author URI: http://liamparker.com/
*/

//Redirect Admin
add_action("admin_init", "pageRedirectMetaBox");
add_action('save_post', 'pageRedirectSave');

function pageRedirectMetaBox(){
	if(function_exists('get_post_types')) {
		$postTypes = get_post_types( array(), 'objects' );
		foreach ($postTypes as $postType) {
			if ($postType->show_ui) {
				add_meta_box("pageRedirect", "Page Redirect", "pageRedirect", $postType->name, "normal", "high");
			}
		}
	}
}

//Setup Meta Box
function pageRedirect(){
	global $post;
	$custom = get_post_custom($post->ID);
	$redirectLink = $custom["redirectLink"][0];
	?>
	<label>Redirect URL: </label><input type='text' name="redirectLink" value="<?php echo $redirectLink; ?>" />
<?php
}

//Save Redirect Options
function pageRedirectSave(){
	global $post;
	if ($_POST["redirectLink"]){
		update_post_meta($post->ID, "redirectLink", $_POST["redirectLink"]);
	}else{
		delete_post_meta($post->ID, "redirectLink");
	}     
}

//Perform Redirect
function pageRedirectRedirect(){
	global $post;
	$redirectLink = get_post_meta( $post->ID, 'redirectLink', true);
	if($redirectLink){
		header("location: $redirectLink");
	}
}
add_action('template_redirect', 'pageRedirectRedirect'); 
?>