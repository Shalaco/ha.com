<?php
/**
 * Template Name: Form Page
 *
 * A page for displaying download and contact forms
 * 
 */

get_header(); ?>
<div class="row-fluid">
	<div class="span12 bottom-bump sub-hero quiz">
	  <section id="secondary" class="widget-area span3 give-me-space bottom-bump" role="complementary">			
		<?php include ('sidebar.php'); ?>
		</section>
		<div class="span9" style="">
			<div class="span12 bottom-bump ">
				<div class="span11 center hero1">
				  <?php if (have_posts()) : ?>

				    <?php while (have_posts()) : the_post(); ?>

				      <?php // Individual Post Styling ?>			
					<h2 class="crimson"><?php the_title(); ?></h2>
					
					<p class="heading">
					<?php the_content();?>
					</p>
				</div>
			</div>
			<div class="span12 sixteen fourpercent main-content bottom-bump" style="">
				<div class="span12" style="border-bottom:dotted 1px #ccc;">
          	<div class="span7 pull-left" >
           <?php echo do_shortcode(types_render_field("left-col", array('raw' => 'true', 'output' => 'html'))); ?>
           </div><!--/span6-->
            <div class="span4 pull-right">
        		 <?php echo do_shortcode(types_render_field("right-col", array('raw' => 'true', 'output' => 'html'))); ?>

           </div><!--/span6-->
									<div class="clearfix"></div>
						     <?php endwhile; ?>

				      <?php // Navigation ?>

				    <?php else : ?>

				      <?php // No Posts Found ?>

				  <?php endif; ?>
		 
						</div>
					</div>	 
					
				</div><!-- /span12 end main content block -->

        </div>	
				  
			</div>
		</div>
	</div>
	<!--/.span12-->
</div>
<?php get_footer(); ?>