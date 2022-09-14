<?php 
/**
 * 
 * Footer File
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>



<footer>
  <div class="container-fluid">
    <div class="jma-footer container">
    <?php wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'menu_class' => 'footer-navigation', 
            'depth' => 1
        )) ?>    
    </div>
  </div>
</footer>
    <?php wp_footer(); ?>
  </body>
</html>