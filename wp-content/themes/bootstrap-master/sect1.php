<?php
/**
 * Template Name: "Section Landing Page"
 *
 * A page for displaying "Section Landing Pages" 
 * 
 */

get_header(); ?>
<div class="row-fluid">
 <?php if (have_posts()) : ?>

      <?php while (have_posts()) : the_post(); ?>

        <?php // Individual Post Styling ?>

	<div class="span12 bottom-bump sub-hero sect1">
    <div class="span5 " style="">
       <h1 class="crimson flush push-up right-align"> <?php echo types_render_field("sub-hd", array('raw' => 'true', 'output' => 'html')); ?></h1>
    </div><!--/span3-->
    <div class="span5" style="">
      <?php the_content();?>
        	
    </div>
   
	</div>	<!--/.span12-->
	 <?php echo do_shortcode(types_render_field("types-main-cntnt", array('raw' => 'true', 'output' => 'html'))); ?>

      <?php endwhile; ?>

        <?php // Navigation ?>

      <?php else : ?>

        <?php // No Posts Found ?>

    <?php endif; ?>

</div>
<div class="row-fluid">
  <div class="span12">
  	<div class="span12 border-gray">
  	  <?php dynamic_sidebar( 'partners' ); ?>
  	</div>
  </div>
</div><!--/row-fluid-->
<?php get_footer(); ?>