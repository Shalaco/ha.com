<?php
/**
 * Template Name: "Artifact"
 *
 * Page for displaying Artifacts
 * 
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
	
        	<div class="span11 social" style="">
        	<h5 class="cyan pull-left">Share This Page:</h5>
        	<div class="btn-group pull-left">
        		<div class="span3 offset3">
        			<span class='st_facebook_large' displaytext='Facebook'></span>
        			<span class='st_pinterest_large' displaytext='Pinterest'></span>
        			<span class='st_twitter_large' displaytext='Tweet'></span>
        			<span class='st_email_large' displaytext='Email'></span>
        			<span class='st_googleplus_large' displaytext='Google +'></span>
        		</div>
        		<!--/.span3-->
        	</div>
        </div>
							<?php endif; ?> 
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