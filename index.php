<?php get_header();?>
    <div id="site-content" class="default_width">
    	<?php if ( have_posts() ) :
			while ( have_posts() ) : the_post();
            	if(get_post_format()):
					get_template_part('content', get_post_format());
				else:	
					get_template_part('content');
				endif;
			endwhile; ?>        
        <?php endif; ?>		
	</div><!-- #site-content -->
<?php get_footer();?>