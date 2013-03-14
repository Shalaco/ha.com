<?php
/**
 * Template Name: Facts 
 *
 * Template for displaying home page content
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
					<?php
					echo '<h2 class="crimson">';
					the_title() ;
					echo '</h2>'; 
			   the_content(); // Individual Post Styling ?>
					<?php endwhile; ?>
					<?php // Navigation ?>
					<?php else : ?>
					<?php // No Posts Found ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="span12 sixteen fourpercent main-content" style="border:solid 6px #e9e9e9;border-radius:6px;margin-left:0px;">
				<div class="btn-group pull-right">
					<!--<a class="btn" data-toggle="dropdown" href="#">
					Download PDF <span class=""><i class="icon-file"></i></span>
					</a>
					<a class="btn print-preview" data-toggle="dropdown" href="#">
					Print Checklist <span class=""><i class="icon-print"></i></span>-->
					</a><?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
				</div>
				<div class="span11">

          <?php echo do_shortcode(types_render_field("top-content", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button" style="">1</button><?php echo do_shortcode(types_render_field("fact1", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">2</button><?php echo do_shortcode(types_render_field("fact2", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">3</button><?php echo do_shortcode(types_render_field("fact3", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">4</button><?php echo do_shortcode(types_render_field("fact4", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">5</button><?php echo do_shortcode(types_render_field("fact5", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">6</button><?php echo do_shortcode(types_render_field("fact6", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">7</button><?php echo do_shortcode(types_render_field("fact7", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">8</button><?php echo do_shortcode(types_render_field("fact8", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">9</button><?php echo do_shortcode(types_render_field("fact9", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 factlet" style="border-bottom:dotted 1px #ccc;">
					<button class="btn btn-primary pull-left crimson" type="button">10</button><?php echo do_shortcode(types_render_field("fact10", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11">
          <?php echo do_shortcode(types_render_field("bttm-content", array('raw' =>
					 'true', 'output' => 'html'))); ?>
				</div>
				<div class="span11 social" style="border-bottom:dotted 1px #ccc;">
					<h5 class="cyan pull-left">Share This Page:</h5>
					<div class="btn-group pull-left">
								<div class="span3 offset3">
			  <span class='st_facebook_large' displayText='Facebook'></span>
<span class='st_pinterest_large' displayText='Pinterest'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
<span class='st_email_large' displayText='Email'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
			</div><!--/.span3-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/.span12-->
</div>
<?php get_footer(); ?>