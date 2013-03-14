<?php
/**
 * Template Name: "Types of Hearing Loss"
 *
 * A page for displaying "Types of Hearing Loss" 
 * 
 */

get_header(); ?>
<div class="row-fluid">
	<div class="span12 bottom-bump sub-hero types">
	  <section id="secondary" class="widget-area span3 give-me-space bottom-bump" role="complementary">			
		<?php include ('sidebar.php'); ?>
		</section>
		<div class="span9" style="">
			<div class="span12 bottom-bump main-content">
				<div class="span11 center hero1 " style="">
				  <?php if (have_posts()) : ?>

				    <?php while (have_posts()) : the_post(); ?>

				      <?php // Individual Post Styling ?>			
				      
					<h2 class="crimson"><?php the_title(); ?></h2>
					<div class="span12 bottom-bump fourpercent " style="">
					   			
					   			
						<p class="heading center crimson">
					  <?php echo do_shortcode(types_render_field("types-subhd", array('raw' => 'true', 'output' => 'html'))); ?>
					</p>		<div class="span4" >
					   <img src="<?php echo do_shortcode(types_render_field("types-img-1", array('raw' => 'true', 'output' => 'html'))); ?>" height="150" width="150" class="shadowed">
					    <p class="center-text green"><?php echo do_shortcode(types_render_field("types-img-1-cpt", array('raw' => 'true', 'output' => 'html'))); ?></p>
					   </div>
					   <div class="span4 " >
					   <img src="<?php echo do_shortcode(types_render_field("types-img-2", array('raw' => 'true', 'output' => 'html'))); ?>" height="150" width="150" class="shadowed">
  <p class="center-text green"><?php echo do_shortcode(types_render_field("types-img-2-cpt", array('raw' => 'true', 'output' => 'html'))); ?></p>
					   </div><div class="span4" >
					   <img src="<?php echo do_shortcode(types_render_field("types-img-3", array('raw' => 'true', 'output' => 'html'))); ?>" height="150" width="150" class="shadowed">
					     <p class="center-text green"><?php echo do_shortcode(types_render_field("types-img-3-cpt", array('raw' => 'true', 'output' => 'html'))); ?></p>
					   </div>

				</div>
									   					  <p><?php echo do_shortcode(types_render_field("types-note", array('raw' => 'true', 'output' => 'html'))); ?></p>
				</div>
			</div>
			<div class="span12 sixteen fourpercent main-content bottom-bump" style="">
				<div class="span11" style="">
          <?php echo do_shortcode(types_render_field("types-main-cntnt", array('raw' => 'true', 'output' => 'html'))); ?>
									<div class="clearfix">
						     <?php endwhile; ?>

				      <?php // Navigation ?>

				    <?php else : ?>

				      <?php // No Posts Found ?>

				  <?php endif; ?>
		 
						</div>
					</div>	 
					
				</div><!-- /span12 end main content block -->
      
        		<div class="clearfix">
        		</div>
      
        	</div>
        </div>	
				  
			</div>
		</div>
	</div>
	<!--/.span12-->
</div>
<?php get_footer(); ?>