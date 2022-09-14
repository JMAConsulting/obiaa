<?php
/**
 * 
 * Hero Section
 * 
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$heroImg = get_theme_mod('obiaa_hero_image');
$mobileHeroImg = get_theme_mod('obiaa_mobile_hero_image');
$titleSuffix = get_theme_mod('obiaa_name_suffix');
civicrm_initialize();
$domain = civicrm_api3('Domain', 'get', [
  'sequential' => 1,
])['values'][0]['name'];
 ?>

<div class="hero hero__main" style="
background-image: url('<?php echo esc_attr($heroImg); ?>')">
</div>
<div class="hero hero__mobile" style="
background-image: url('<?php echo esc_attr($mobileHeroImg); ?>')">
</div>

<div class="hero__content">
  <div class="container">
    <div class="logo">
      <?php if(has_custom_logo()) : ?>
      <a href="<?php echo esc_url(home_url("/")); ?>"><img src="<?php echo esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] ); ?>" alt="<?php esc_attr_e(get_the_title()); ?>" /></a>
      <?php elseif((get_bloginfo('name') !== '') && !has_custom_logo() ) :  ?>
		
        <h1><?php esc_html(bloginfo('name')); ?> <?php echo esc_html($titleSuffix); ?></h1>
      <?php else : ?>
        <h1><?php echo esc_html($domain); ?></h1>
      <?php endif; ?>
    </div>
  </div>
</div>

