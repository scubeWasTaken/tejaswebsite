<?php
/*
 * Template Name: Front Page Template
 */
get_header('homepage');
?>

<div class="content">
  <div class="gridContainer">
   <?php 
      while ( have_posts() ) : the_post();
        get_template_part( 'template-parts/content', 'page' );
      endwhile;
     ?>
  </div>
</div>

<?php get_footer(); ?>