<div id="site-header" class="default_width clearfix">
	<div id="logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png" alt="<?php _e('Logo', _TEXTDOMAIN_); ?>">
        </a>
    </div><!-- #logo -->
	<?php wp_nav_menu(array('theme_location' => 'primary', 'container_class' => 'clear', 'menu_class' => 'nav-menu clearfix')); ?>
</div><!-- #site-header -->