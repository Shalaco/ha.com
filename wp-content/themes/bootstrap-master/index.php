<?php
/** Integrated from bootstrap-master 2013 02.11 21:00 
 *  from starter-template.html
 */

get_header(); ?>
<div class="row-fluid">
	<div class="span12 bottom-bump sub-hero types">
	  <section id="secondary" class="widget-area span3 give-me-space bottom-bump" role="complementary">			
		<?php include ('sidebar-2.php'); ?>
		</section>
		<div class="span9" style="">
			<div class="span12 bottom-bump ">
				<div class="span11 center hero1 bottom-bump" style="">
				  <?php if (have_posts()) : ?>

				    <?php while (have_posts()) : the_post(); ?>

				      <?php // Individual Post Styling ?>			
				      
					<h2 class="crimson"><?php the_title(); ?></h2>
		
					   			<?php the_content(); ?>
			
		

				</div>
				<?php if (types_render_field("types-main-cntnt", array())) : ?>
				<div class="span12 sixteen fourpercent main-content bottom-bump" style="">
          <?php echo do_shortcode(types_render_field("types-main-cntnt", array('raw' => 'true', 'output' => 'html'))); ?>
					<div class="clearfix"></div>
				</div>
									<?php endif; ?>	 
											     <?php endwhile; ?>

				      <?php // Navigation ?>

				    <?php else : ?>

				      <?php // No Posts Found ?>

				  <?php endif; ?>

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