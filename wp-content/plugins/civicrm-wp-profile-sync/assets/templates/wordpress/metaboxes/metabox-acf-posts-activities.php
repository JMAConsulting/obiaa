<?php
/**
 * WordPress Posts to CiviCRM Activities sync template.
 *
 * Handles markup for the WordPress Posts to CiviCRM Activities meta box.
 *
 * @package CiviCRM_WP_Profile_Sync
 * @since 0.8
 */

?><!-- assets/templates/wordpress/metabox-activities-posts.php -->
<?php $prefix = 'cwps_acf_post_to_activity'; ?>

<div class="cwps_acf_wrapper <?php echo $prefix; ?>">

	<p><?php esc_html_e( 'Select which Post Types you want to sync to their corresponding CiviCRM Activity Types.', 'civicrm-wp-profile-sync' ); ?></p>

	<?php if ( ! empty( $activity_post_types ) ) : ?>
		<table class="form-table">

			<?php foreach ( $activity_post_types as $activity_post_type => $label ) : ?>

				<?php $identifier = $prefix . '_' . $activity_post_type; ?>
				<?php $stop = ''; ?>

				<?php if ( 'fgffgs' == get_option( '_' . $identifier . '_offset', 'fgffgs' ) ) : ?>
					<?php $button = __( 'Sync Now', 'civicrm-wp-profile-sync' ); ?>
				<?php else : ?>
					<?php $button = __( 'Continue Sync', 'civicrm-wp-profile-sync' ); ?>
					<?php $stop = $identifier . '_stop'; ?>
				<?php endif; ?>

				<tr valign="top">
					<th scope="row"><label for="<?php echo $identifier; ?>"><?php echo esc_html( $label ); ?></label></th>
					<td><input type="submit" id="<?php echo $identifier; ?>" name="<?php echo $identifier; ?>" data-security="<?php echo esc_attr( wp_create_nonce( $identifier ) ); ?>" value="<?php echo $button; ?>" class="button-secondary" />
						<?php if ( ! empty( $stop ) ) : ?>
							<input type="submit" id="<?php echo $stop; ?>" name="<?php echo $stop; ?>" value="<?php esc_attr_e( 'Stop Sync', 'civicrm-wp-profile-sync' ); ?>" class="button-secondary" />
						<?php endif; ?>
					</td>
				</tr>

				<tr valign="top">
					<td colspan="2" class="progress-bar progress-bar-hidden"><div id="progress-bar-cwps_acf_post_to_activity_<?php echo $activity_post_type; ?>"><div class="progress-label"></div></div></td>
				</tr>

			<?php endforeach; ?>

		</table>
	<?php else : ?>

		<div class="notice notice-warning inline" style="background-color: #f7f7f7;">
			<p><?php esc_html_e( 'No synced Activity Post Types found.', 'civicrm-wp-profile-sync' ); ?></p>
		</div>

	<?php endif; ?>

</div>

