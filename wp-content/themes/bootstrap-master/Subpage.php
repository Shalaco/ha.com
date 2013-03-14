<?php
/**
 * Template Name: Subpage 
 *
 * Template for Sub Pages
 */

get_header(); ?>
<div class="row-fluid">
	<div class="span12 bottom-bump sub-hero facts">
	  <section id="secondary" class="widget-area span3 give-me-space bottom-bump" role="complementary">			
		<?php include ('sidebar.php'); ?>
		</section>
		<!--<div class="span12 fourpercent" style="border:solid 2px #ccc; border-radius:6px;margin-left:0px">
			<h5 class="cyan uppercase underline-grey">Take Action</h5>
			<button class="btn btn-large btn-block crimson large-icon-download" type="button" style=""><span class="pull-right ">DOWNLOAD OUR <br/>HEARING AID GUIDE</span></button>
			<button class="large-icon-phone btn btn-large btn-block crimson " type="button"><span class="pull-right ">CONTACT A HEARING<br/>CARE PROVIDER</span></button>
		</div>-->
		<div class="span9" style="">
			<div class="span12 bottom-bump ">
				<div class="span11 center hero1">
					<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
					<?php ?>
					<h3 class="crimson"><?php the_title(); ?></h3>
			   <p class="heading"><?php the_content(); ?> </p>
					
				</div>
			</div>
			<div class="span12 sixteen fourpercent main-content" style="border:solid 6px #e9e9e9;border-radius:6px;margin-left:0px;">
			  
          <?php echo do_shortcode(types_render_field("subpage-content", array('raw' =>
					 'true', 'output' => 'html'))); 
					 ?>
					
					 			  
          <?php $show_subpages = types_render_field("child-sections", array('raw' => 'true', 'output' => 'html')); 
                if ($show_subpages) {
      
        	$mypages = get_pages( array( 'child_of' => $post->ID, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );

        	foreach( $mypages as $page ) {		
        		$content = $page->post_content;
        		if ( ! $content ) // Check for empty page
        			continue;

        		$content = apply_filters( 'the_content', $content );
        	?>
        		<h4 class="crimson"><?php echo $page->post_title; ?></a></h4>
        		<div class="entry"><?php echo $content; ?></div>
        		<a class="cta pull-right" href="<?php echo get_page_link( $page->ID ); ?>">Read More</a><br/><hr class="clearfix"/>
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
        	<?php
        	}	
      
                     
                }
                else{ }
                
                ?>
                
                <?php endwhile; ?>
					<?php // Navigation ?>
					<?php else : ?>
					<?php // No Posts Found ?>
					<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<!--/.span12-->
</div>
<?php get_footer(); ?>