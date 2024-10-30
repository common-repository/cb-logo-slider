<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

//get options
$cbls_munber_of_images = get_option('cbls_munber_of_images');
$cbls_controls = get_option('cbls_controls');
$cbls_pagination = get_option('cbls_pagination');
$cbls_slide_speed = get_option('cbls_slide_speed');
$cbls_navigation_text_next = get_option('cbls_navigation_text_next');
$cbls_navigation_text_prev = get_option('cbls_navigation_text_prev');

//set options if options are null
if($cbls_munber_of_images == ''){ $cbls_munber_of_images = 5;}
if($cbls_controls == ''){ $cbls_controls = true;}
if($cbls_pagination == ''){ $cbls_pagination = true;}
if($cbls_slide_speed == ''){ $cbls_slide_speed = 1000;}
if($cbls_navigation_text_next == ''){ $cbls_navigation_text_next = '>';}
if($cbls_navigation_text_prev == ''){ $cbls_navigation_text_prev = '<';}

//sanitize all post values
$add_opt_submit = sanitize_text_field( $_POST['add_opt_submit'] );
if($add_opt_submit != '' && wp_verify_nonce($_POST['add_opt_submit'], 'add_opt_submit')) {
    
	$cbls_munber_of_images = sanitize_text_field( $_POST['cbls_munber_of_images'] );
	$cbls_controls = sanitize_text_field( $_POST['cbls_controls'] );
	$cbls_pagination = sanitize_text_field( $_POST['cbls_pagination'] );
	$cbls_slide_speed = sanitize_text_field( $_POST['cbls_slide_speed'] );
	$cbls_navigation_text_next = sanitize_text_field( $_POST['cbls_navigation_text_next'] );
	$cbls_navigation_text_prev = sanitize_text_field( $_POST['cbls_navigation_text_prev'] );
	$saved= sanitize_text_field( $_POST['saved'] );


    if(isset($cbls_munber_of_images) ) {
		update_option('cbls_munber_of_images', $cbls_munber_of_images);
    }
	
	if(isset($cbls_controls) ) {
		update_option('cbls_controls', $cbls_controls);
    }
	 if(isset($cbls_pagination) ) {
		update_option('cbls_pagination', $cbls_pagination);
    }
	if(isset($cbls_slide_speed) ) {
		update_option('cbls_slide_speed', $cbls_slide_speed);
    }
	if(isset($cbls_navigation_text_next) ) {
		update_option('cbls_navigation_text_next', $cbls_navigation_text_next);
    }
	if(isset($cbls_navigation_text_prev) ) {
		update_option('cbls_navigation_text_prev', $cbls_navigation_text_prev);
    }
	if($saved==true) {
		$message='saved';
	} 
}

	if ( $message == 'saved' ) {
		echo ' <div class="added-success"><p><strong>Settings Saved.</strong></p></div>';
	}
?>
   
<div class="wrap cynob-facebook-post-setting">
	<form method="post" id="settingForm" action="">
	<h2><?php _e('Logo Slider Setting','');?></h2>
	<table class="form-table">
	<tr valign="top">
		<th scope="row" style="width: 370px;">
			<label for="cbls_munber_of_images"><?php _e('Number of images to show','');?></label>
		</th>
		<td><input type="text" name="cbls_munber_of_images" size="10" value="<?php echo $cbls_munber_of_images; ?>" />
	
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" style="width: 370px;">
			<label for="cbls_munber_of_images"><?php _e('Controls','');?></label>
		</th>
		<td>
			<select style="width:120px" name="cbls_controls" id="cbls_controls">
				<option value='true' <?php if($cbls_controls == 'true') { echo "selected='selected'" ; } ?>>True</option>
				<option value='false' <?php if($cbls_controls == 'false') { echo "selected='selected'" ; } ?>>False</option>
			</select>
			<br />
			<em><?php _e('Show Left, Right arrow button.', ''); ?></em>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row" style="width: 370px;">
			<label for="cbls_pagination"><?php _e('Pagination','');?></label>
		</th>
		<td>
		<select style="width:120px" name="cbls_pagination" id="cbls_pagination">
			<option value='true' <?php if($cbls_pagination == 'true') { echo "selected='selected'" ; } ?>>True</option>
			<option value='false' <?php if($cbls_pagination == 'false') { echo "selected='selected'" ; } ?>>False</option>
		</select>
		<br />
		<em><?php _e('Show pagination.', ''); ?></em>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row" style="width: 370px;">
			<label for="cbls_slide_speed"><?php _e('Slide Speed', '');?></label>
		</th>
		<td>
		<input type="text" name="cbls_slide_speed" size="10" value="<?php echo $cbls_slide_speed; ?>" />
	   <br />
	   <em><?php _e('Slide Speed in millisecond. (Ex: 1000)', ''); ?></em>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" style="width: 370px;">
			<label for="cbls_slide_speed"><?php _e('Navigation Text','');?></label>
		</th>
		<td>
		Prev: <input type="text" name="cbls_navigation_text_prev" size="10" value="<?php echo $cbls_navigation_text_prev; ?>" />
		<br />
	   Next:  <input type="text" name="cbls_navigation_text_next" size="10" value="<?php echo $cbls_navigation_text_next; ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<p class="submit">
			<input type="hidden" name="saved"  value="saved"/>
			<input type="submit" name="add_opt_submit" class="button-primary" value="Save Changes" />
			<?php if(function_exists('wp_nonce_field')) wp_nonce_field('add_opt_submit', 'add_opt_submit'); ?>
			</p>
		</td>
	</tr>
	</table>
   </form>
</div>