<?php
/**
 * Template Name: Home Page
 *
 * Template for displaying home page content
 */

get_header(); 
?>
    <div class="container">
		<div class="row-fluid  slideshow bottom-bump">
			<div class="span12 hero ">
			  <?php if (have_posts()) : ?>
			   <?php while (have_posts()) : the_post(); ?>

			     <?php // Individual Post Styling ?>
           <?php get_the_title('<h3>', '</h3>', TRUE); 
           the_content();?>
			   <?php endwhile; ?>
          
			     <?php // Navigation ?>

			   <?php else : ?>

			     <?php // No Posts Found ?>

			  <?php endif; ?>
			 <!-- <img src="<?php bloginfo('template_url'); ?>/img/hero-fpo-1200x407.png" width="1200" height="407" alt="Hero Fpo 1200x407">-->
			</div><!--/.span12-->
		</div><!-- /row-fluid-->
		<div class="row-fluid">
			<div class="span6">
			  <a href="<?php echo do_shortcode(types_render_field("home-cta1-url", array('raw' => 'true', 'output' => 'html'))); ?>">
        		<button class="btn btn-large btn-block bottom-bump crimson large-icon-download" type="button"><i>
        		  <?php if (types_render_field("cta1-image", array())) : ?>
        		  <img src="<?php echo do_shortcode(types_render_field("cta1-image", array('raw' => 'true', 'output' => 'html'))); ?>" class="pullright nopad">
<?php endif; ?>

        		  
        		  
        		  </i>  <?php echo types_render_field("home-cta1", array('raw' => 'true', 'output' => 'html')); ?> </button></a>
			</div><!--/.span12-->
			<div class="span6">
		    <a href="<?php echo do_shortcode(types_render_field("home-cta2-url", array('raw' => 'true', 'output' => 'html'))); ?>">
        		<button class="btn btn-large btn-block bottom-bump crimson" type="button"><i>
        		          		  <?php if (types_render_field("cta2-image", array())) : ?>
        		  <img src="<?php echo do_shortcode(types_render_field("cta2-image", array('raw' => 'true', 'output' => 'html'))); ?>" class="pullright nopad">
<?php endif; ?></i> <?php echo do_shortcode(types_render_field("home-cta2", array('raw' => 'true', 'output' => 'html'))); ?></button>
        </a>
			</div><!--/.span12-->
		</div><!-- /row-fluid-->
	<div class="row-fluid news">
		<div class="span12">
  		<div class="span12">
  		  
			
			


			 

      <?php if (types_render_field("news-img", array())) : ?>
       <img src="<?php echo do_shortcode(types_render_field("news-img", array('raw' => 'true', 'output' => 'html'))); ?>" width="188" height="188" alt="News" class="pull-left">
      <?php endif; ?>
		<?php echo do_shortcode(types_render_field("home-news", array('raw' => 'true', 'output' => 'html'))); ?>

				</div>
			</div>
		</div>	
		
        		<div class="row-fluid tertiarry ">
			<div class="span4 alpha bottom-bump">
			      <a href="<?php echo do_shortcode(types_render_field("promobox1_link", array('raw' => 'true', 'output' => 'html'))); ?>">
			  <div class="span12 fourpercent">
			      <?php echo do_shortcode(types_render_field("promobox1", array('raw' => 'true', 'output' => 'html'))); ?> 
			  </div>
			  </a>
			</div><!--/.span4-->
			
			<div class="span4 beta bottom-bump">
			   <a href="<?php echo do_shortcode(types_render_field("promobox2_link", array('raw' => 'true', 'output' => 'html'))); ?>">
			  <div class="span12 fourpercent">
			    <?php echo do_shortcode(types_render_field("promobox2", array('raw' => 'true', 'output' => 'html'))); ?> 
			  </div>
			  </a>
			</div><!--/.span4-->
			<div class="span4 gamma bottom-bump">
			   <a href="<?php echo do_shortcode(types_render_field("promobox2_link", array('raw' => 'true', 'output' => 'html'))); ?>">
		    <div class="span12 fourpercent">
		        <?php echo do_shortcode(types_render_field("promobox3", array('raw' => 'true', 'output' => 'html'))); ?> 
			  </div>
			  </a>
			</div><!--/.span4-->

    </div> <!-- /container -->
    <?php get_footer(); ?>
    

 