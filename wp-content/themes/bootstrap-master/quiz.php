<?php
/**
 * Template Name: Quiz Page
 *
 * A page for displaying quiz content
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
					<?php echo types_render_field("quiz-herotext", array('raw' => 'true', 'output' => 'html')); ?> 
					</p>
				</div>
			</div>
			<div class="span12 sixteen fourpercent main-content bottom-bump" style="">
				<div class="span11" style="border-bottom:dotted 1px #ccc;">
          <?php the_content();?>
									<div class="clearfix">
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
	<!--/.span12-->
</div>
<?php get_footer(); ?>