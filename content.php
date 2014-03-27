<div id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
	<div class="entry-header">
    	<?php if(is_singular()):?>
        	<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title();?></a></h1>
        <?php else : ?>
        	<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h3>
        <?php endif; ?>
    </div>
    <?php if ( is_search() ) : ?>
        <div class="entry-summary rte">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
    <?php else : ?>
        <div class="entry-content rte">
            <?php the_content( __( 'Čítat celý článek', _TEXTDOMAIN_ ) ); ?>
        </div><!-- .entry-content -->
    <?php endif; ?>
</div>