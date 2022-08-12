<?php $obiaaLogo = get_theme_mod('obiaa_logo_image');  ?>
<header class="container-fluid main-header <?php echo  is_front_page () ?  'header-height' : null; ?> ">
      <div class="header container">
        <div class="logo">
          <a href="<?php echo esc_url(home_url("/")); ?>"><img src="<?php echo esc_url( $obiaaLogo ); ?>" alt="<?php esc_attr_e(get_the_title()); ?>" /></a>
        </div>
        <!-- main-menu -->
        <nav class="navigation">
        <?php wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_class' => 'main-navigation', 
                'depth' => 2
            )) ?>    

        </nav>
        <button class="mobile-btn">
          <div class="line line-1"></div>
          <div class="line line-2"></div>
          <div class="line line-3"></div>
        </button>
      </div>
      <?php if(is_front_page ()) : ?>
   <!-- Hero Section -->
   <?php get_template_part('hero'); ?>
   <?php endif; ?>
    </header>

    <!-- Mobile Container -->
    <nav class="mobile__navigation">
    <?php wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_class' => 'mobile__main-navigation', 
                'depth' => 2
            )) ?>    

    </nav>