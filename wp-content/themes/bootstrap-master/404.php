<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 *
 * 
 */

get_header(); ?>
<div class="row-fluid">
	<div class="span12 bottom-bump sub-hero four-o-four ">
    <div class="span3 offset3" style="">
    <h1 class="crimson flush">We're Sorry</h1>
    </div><!--/span3-->
    <div class="span5" style="">
      <p> The page you requested is not available<br/><a href="<?php bloginfo('url'); ?>">Click here to visit our homepage</a></p>
        		<div class="span12 no-margin">
        		<div class="span5 no-margin">
        			<a href="/take-action/download-the-hearing-aid-guide/"><button style="" type="button" class="btn unique crimson"><span class="pull-left "> DOWNLOAD OUR <br>HEARING AID GUIDE</span></button></a>
        				</div>
        			<div class="span5 ">
        			<a href="/take-action/contact-a-hearing-care-provider/"><button style="" type="button" class="btn unique crimson large-icon-phone"><span class="pull-left "> CONTACT A HEARING <br>CARE PROVIDER</span></button></a>
        			</div>
        
        	</div>
    </div>
	</div>	<!--/.span12-->
</div>
<?php //$user_info = get_userdata(1);
      //echo 'Username: ' . $user_info->user_login . "\n";
      //echo 'User level: ' . $user_info->user_level . "\n";
      //echo 'User ID: ' . $user_info->ID . "\n";
?>
<?php get_footer(); ?>