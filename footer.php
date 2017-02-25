<?php
/**
* The template for displaying the footer
*
* Contains the closing of the #content div and all content after.
*
*/
?>
<div class="footer">
  <div class="gridContainer">
    <div class="row">
      <div class="footer_left">
        <p>
          <?php echo reiki_copyright(); ?>
        </p>
      </div>
      <div class="footer_right">
        <?php reiki_logo(true); ?>
      </div>
    </div>
  </div>
</div>
<?php wp_footer();?>