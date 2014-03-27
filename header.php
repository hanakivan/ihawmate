<!DOCTYPE html>
<!--[if IE 6]>
<html class="ie ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
    	<meta charset="<?php echo esc_attr( get_bloginfo('charset') ); ?>">
        <title><?php _site_title();?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">      
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <?php wp_head();?>
	</head>
	<body <?php body_class(); ?>>
    	<div id="masterhead" class="full_width">        	
			<?php get_template_part('content', 'header');?>
        </div><!-- #masterhead end -->
        <div id="masterwrap" class="full_width"><!-- closing in footer.php-->