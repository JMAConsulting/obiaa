<?php
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch -- both free and premium versions have different plugin slugs but share the same text domain to ensure consistency and simplify translation management.
if (!defined('ABSPATH')) die('No direct access.');
$easy_updates_manager_url = 'https://easyupdatesmanager.com/buy/?utm=eum-premium-tab';
$updraftcentral_url = 'https://updraftplus.com/updraftcentral/?utm=eum-premium-tab';
$updraftplus_url = 'https://updraftplus.com/?utm=eum-premium-tab';
$easy_updates_manager_downloads = '200,000';
?>
<div class="advanced-premium">
	<h3><?php esc_html_e('Get Easy Updates Manager Premium', 'stops-core-theme-and-plugin-updates'); ?></h3>
	<p class="mpsum-medium"><?php
	
		esc_html_e('Get many more features with Easy Updates Manager Premium.', 'stops-core-theme-and-plugin-updates');
		
		/* translators: 1: Link to the feature list, 2: Link to the store. */
		printf(' '.esc_html__('Check out the video and %1$s below, or %2$s', 'stops-core-theme-and-plugin-updates'), '<a href="#mpsum-advanced-premium-features">'.esc_html__('feature list', 'stops-core-theme-and-plugin-updates').'</a>', '<a href="' . esc_url($easy_updates_manager_url) . '">'.esc_html__('go to our store.', 'stops-core-theme-and-plugin-updates').'</a>');
		
	?></p>
	<?php
	// @codingStandardsIgnoreStart
	?>
	<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/289883791?color=df6926&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
	<p class="mpsum-medium mpsum-caption"><a href="<?php echo esc_attr($easy_updates_manager_url); ?>"><?php esc_html_e('Find out about the advantages of upgrading to Easy Updates Manager Premium', 'stops-core-theme-and-plugin-updates'); ?></a></p>
	<div class="mpsum-advanced-premium-features" id="mpsum-advanced-premium-features">
		<h3><?php esc_html_e('Premium features include:', 'stops-core-theme-and-plugin-updates');?></h3>
		<ul class="mpsum-advanced-premium-list">
			<li><strong><?php esc_html_e('Safe mode', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e("Prevent updates that are not compatible with your current WordPress version or your server's PHP version.", 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Scheduling updates', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php esc_html_e('Choose the most convenient time to run your automatic updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('External logging', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Sends alert when updates have taken place.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Anonymization', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e("Controls what is sent to the WordPress.org API; stop sending unnecessary/analytics data.", 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Delayed updates', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Delays automatic updates by a set time to prevent installing short-lived (e.g. buggy) updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Automatic backups', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php printf(esc_html__('Takes a backup before your website is updated via an integration with %s', 'stops-core-theme-and-plugin-updates'), '<a href="'.esc_url($updraftplus_url).'">UpdraftPlus</a>'); ?></span></li>
			<li><strong>UpdraftCentral</strong><span class="mpsum-list-description"><?php printf(esc_html__('Fully integrates with %s for centralized remote control.', 'stops-core-theme-and-plugin-updates'), '<a href="'.esc_url($updraftcentral_url).'">UpdraftCentral</a>'); ?></span></li>
			<li><strong><?php esc_html_e('Log clearing', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Automatically prune your logs via scheduled deletion of older entries.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Import/export', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php esc_html_e('Export your settings from one site to another for quicker setup.', 'stops-core-theme-and-plugin-updates');?></span></li>
			<li><strong><?php esc_html_e('E-mail notifications', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Send weekly or monthly reports of pending updates.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('White-label', 'stops-core-theme-and-plugin-updates'); ?></strong> <?php esc_html_e('Customize what branding and notices your clients see in the plugin settings.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Check plugins', 'stops-core-theme-and-plugin-updates');?></strong><span class="mpsum-list-description"><?php esc_html_e('Runs a check for plugins that have been removed from the WordPress directory.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Webhook', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Integrates with third-party services to allow automatic updates to be triggered via cron or tools like Zapier.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
			<li><strong><?php esc_html_e('Export logs', 'stops-core-theme-and-plugin-updates'); ?></strong><span class="mpsum-list-description"><?php esc_html_e('Exports logs for your chosen date range for printing or a CSV/JSON for auditing.', 'stops-core-theme-and-plugin-updates'); ?></span></li>
		</ul>
		<strong><?php esc_html_e('All with premium support, and more planned!', 'stops-core-theme-and-plugin-updates'); ?></strong> <a href="<?php echo esc_url($easy_updates_manager_url); ?>"><?php esc_html_e('Go to our store to get it.', 'stops-core-theme-and-plugin-updates'); ?></a>
	</div>
	<div class="eum-button-cta">
		<a href="<?php echo esc_url($easy_updates_manager_url); ?>"><?php esc_html_e('Get Premium Today!', 'stops-core-theme-and-plugin-updates'); ?></a>
	</div>
</div><!-- advanced-premium -->
<?php
	// @codingStandardsIgnoreEnd