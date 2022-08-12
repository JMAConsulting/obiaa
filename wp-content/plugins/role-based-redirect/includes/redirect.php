   <?php
if (!defined('ABSPATH')) exit;	
	global $wpdb;
	if( isset( $_POST['role_url_submit'] ) ) {
	if (isset( $_POST['url_redirect'] ) && wp_verify_nonce($_POST['url_redirect'], 'add-urls') ) {
		$rbrurls_redirect = $wpdb->prefix . 'rbrurls_redirect';
		$role_type=sanitize_text_field($_POST['role_userrole']);
		$url_login_dropdown=sanitize_text_field($_POST['url_login_dropdown']);
		$url_login_textbox=sanitize_text_field($_POST['url_login_textbox']);				
		$url_logout_dropdown=sanitize_text_field($_POST['url_logout_dropdown']);
		$url_logout_textbox=sanitize_text_field($_POST['url_logout_textbox']);
		$adminbar=isset($_POST['adminbar']);
		if ($adminbar == null )
		{
			$adminbar = "no";
		}
		else {
			$adminbar=sanitize_text_field($_POST['adminbar']);
		}
		$restrictdash=isset($_POST['restrictdash']);
		if ($restrictdash == null )
		{
			$restrictdash = "no";
		}
		else {
			$restrictdash=sanitize_text_field($_POST['restrictdash']);
		}
		
		if (!empty($url_login_dropdown)||!empty($url_login_textbox))
		{
			$result=  $wpdb->insert( $rbrurls_redirect,
			array(
			'role_type' => $role_type,
			'url_login_dropdown' => $url_login_dropdown,
			'url_login_textbox'=> $url_login_textbox,
			'url_logout_dropdown' => $url_logout_dropdown,
			'url_logout_textbox'=> $url_logout_textbox,
			'adminbar' => $adminbar,
			'restrict_dashboard'=> $restrictdash
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
			);
			
		}
		else {
			
			echo "<script>alert('Please Select Page or Url')</script>";
		}
	}
	}
	
	if( isset( $_POST['role_url_update'] ) ) {
	if (isset( $_POST['url_redirect'] ) && wp_verify_nonce($_POST['url_redirect'], 'add-urls') ) {
		$rbrurls_redirect = $wpdb->prefix . 'rbrurls_redirect';
		$role_type=sanitize_text_field($_POST['role_userrole']);
		$url_login_dropdown=sanitize_text_field($_POST['url_login_dropdown']);
		$url_login_textbox=sanitize_text_field($_POST['url_login_textbox']);				
		$url_logout_dropdown=sanitize_text_field($_POST['url_logout_dropdown']);
		$url_logout_textbox=sanitize_text_field($_POST['url_logout_textbox']);
		$adminbar=isset($_POST['adminbar']);
		if ($adminbar == null )
		{
			$adminbar = "no";
		}
		else {
			$adminbar=sanitize_text_field($_POST['adminbar']);
		}
		$restrictdash=isset($_POST['restrictdash']);
		if ($restrictdash == null )
		{
			$restrictdash = "no";
		}
		else {
			$restrictdash=sanitize_text_field($_POST['restrictdash']);
		}
		//if (!empty($url_login_dropdown)||!empty($url_login_textbox))
        //{
			$wpdb->update( $rbrurls_redirect,
			array(
			'url_login_dropdown' => $url_login_dropdown,
			'url_login_textbox'=> $url_login_textbox,
			'url_logout_dropdown' => $url_logout_dropdown,
			'url_logout_textbox'=> $url_logout_textbox,
			'adminbar' => $adminbar,
			'restrict_dashboard'=> $restrictdash
			),
			array( 'role_type' => $role_type )
			);
		//}
		//else {
			
		//	echo "<script>alert('Please Select Page or Url')</script>";
		//}
	}
	}
	global $wp_roles; 	
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}rbrurls_redirect" );
	$arr = array();
	foreach ( $results as $result) {			
		//$array = [$result->role_type];
		array_push($arr, $result->role_type);
	}
?>

<div class="container" >
	<?php	
	    $fetchlogindrop= "";		 
		$fetchlogintext= "";
		$fetchlogoutdrop= "";
		$fetchlogouttext= "";
		$fetchadminbar= "";
		$fetchrestrict_dashboard= "";
		$fetchrole_type= "";
	foreach ( $wp_roles->roles as $key=>$value ): 		
		$urls = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}rbrurls_redirect WHERE `role_type` = '$key'" );
        if( !empty( $urls[0]) ){
		$fetchlogindrop = $urls[0]->url_login_dropdown;		 
		$fetchlogintext = $urls[0]->url_login_textbox;
		$fetchlogoutdrop = $urls[0]->url_logout_dropdown;
		$fetchlogouttext = $urls[0]->url_logout_textbox;	
		$fetchadminbar = $urls[0]->adminbar;
		$fetchrestrict_dashboard = $urls[0]->restrict_dashboard;
		$fetchrole_type= $urls[0]->role_type;
		}			?>
	<strong><?php echo $value['name']; ?></strong>
	<form action="" method="post">
		<input type="hidden" name="role_userrole" Value="<?php echo $key; ?>"/>
		<?php wp_nonce_field('add-urls','url_redirect'); ?>
		<div class="row">
			<div class="left">
				<label>Select Page For Login Redirect</label>
			</div>
			<div class="right">
				<select name="url_login_dropdown" class="select_url_login">
					<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
					<?php $pages = get_pages(); foreach ( $pages as $page ) { ?>
						<option value="<?php echo get_page_link( $page->ID ); ?>"<?php  if ($key == $fetchrole_type) {
						selected( $fetchlogindrop, get_page_link( $page->ID ) ); }  ?>><?php echo $page->post_title; ?></option><?php }	?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="left">
				<label for="subject">Or Custom Login Redirect URL</label>
			</div>
			<div class="right">
				<input type="text" name="url_login_textbox" Value="<?php   if ($key == $fetchrole_type) { if( isset($fetchlogintext)){  echo $fetchlogintext; } }?>" placeholder="http://localhost/wordpress/sample-page/"/>
			</div>
		</div>
		<div class="row">
			<div class="left">
				<label>Select Page For Logout Redirect</label>
			</div>
			<div class="right">
				<select name="url_logout_dropdown">
					<option value=""><?php echo esc_attr( __( 'Select page' ) ); ?></option>
					<?php 	$pages = get_pages(); 	foreach ( $pages as $page ) { ?>
						<option value="<?php echo get_page_link( $page->ID ); ?>"<?php  if ($key == $fetchrole_type) { selected( $fetchlogoutdrop, get_page_link( $page->ID ) );} ?>><?php echo $page->post_title; ?></option><?php } ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="left">
				<label for="subject">Or Custom Logout Redirect URL</label>
			</div>
			<div class="right">
				<input type="text" name="url_logout_textbox" Value="<?php  if ($key == $fetchrole_type) {  if( isset($fetchlogouttext)){ echo $fetchlogouttext;}}?>" placeholder="http://localhost/wordpress/sample-page/"/>
			</div>
		</div>
		<div class="row">
			<div class="left">
				<label for="subject">Hide Adminbar</label>
			</div>
			<div class="right">
				<input type="checkbox" name="adminbar" value="yes"<?php if ($key == $fetchrole_type) { if ($fetchadminbar == "yes") { echo "checked='checked'"; } }?> />
			</div>
		</div>
		<?php if ($key != "administrator") { ?>
			<div class="row">
				<div class="left">
					<label for="subject">Restrict Dashboard Access</label>
				</div>
				<div class="right">
					<input type="checkbox" name="restrictdash" value="yes"<?php if ($key == $fetchrole_type) { if ($fetchrestrict_dashboard == "yes") { echo "checked='checked'"; } } ?> />
				</div>
			</div>
		<?php } ?>
		<div class="row">
			<div class="left"></div>
			<div class="right">
				<?php			
					if ( in_array($key,$arr) ){ ?>
					<input type="submit"  Value="Update"/>
					<input type="hidden" name="role_url_update" />
					<?php } else { ?>
					<input type="submit"  Value="Save"/>
					<input type="hidden" name="role_url_submit" />
					<?php } 			
				?>
			</div>
		</div>
	</form>
	<?php  endforeach; ?>
</div>