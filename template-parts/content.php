<div id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post' ); ?>>
  <h3 class="blog-title">
    <a href="<?php the_permalink(); ?>" rel="bookmark">
      <?php the_title(); ?>
    </a>
  </h3>
 
  <?php  get_template_part('template-parts/content-post-header'); ?>

    <?php
      if ( has_post_thumbnail() ):
    ?>
      <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail(); ?>
      </a>  
    <?php 
      endif;
      
      the_excerpt();
    ?>
</div>