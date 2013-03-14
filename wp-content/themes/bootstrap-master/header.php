<!DOCTYPE html>
<html lang="en">
  <head>
    
    <!-- Header and Navigation on iPad  portrait landscape // iphone portrait and landscape  // Desktop !Retina  -->
    <meta charset="utf-8">
    <title><?php bloginfo('description'); ?> | <?php
if ( is_404() ) {
  echo 'Page Not Found';
}
?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Le styles -->
    <link href="<?php bloginfo('template_url'); ?>/docs/assets/css/bootstrap.css" rel="stylesheet">
    <!-- <link href="<?php bloginfo('template_url'); ?>/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">-->
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/docs/assets/css/custom.css" type="text/css" media="screen" title="no title" charset="utf-8">
   <!--<link href="http://www.ozoneclients.com/custom.css" rel="stylesheet">-->
   <!--  <link href="http://www.ozoneclients.com/style.css" rel="stylesheet">-->
   <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style.css" type="text/css" media="screen" title="no title" charset="utf-8">
   <script src="<?php bloginfo('template_url'); ?>/docs/assets/js/fontsizer.js" type="text/javascript" charset="utf-8"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!-- custom gradients for IE9 add globally-->
    <!--[if gte IE 9]>
      <style type="text/css">
        .gradient {
          filter: none;
        }
    </style>
    <![endif]-->
    <!-- 
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_url'); ?>/docs/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_url'); ?>/docs/assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_url'); ?>/docs/assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_url'); ?>/docs/assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/docs/assets/ico/favicon.png">-->
 <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/example/css/print.css" type="text/css" media="print" />
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/src/css/print-preview.css" type="text/css" media="screen">
    <script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>
    <script src="<?php bloginfo('template_url'); ?>/src/jquery.print-preview.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php bloginfo('template_url'); ?>/docs/assets/js/derp.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        
        $(function() {
          
            $('a.print-preview').printPreview();
           
        });
        
        fontsizer({excludes: Array()});
        
        jQuery(document).ready(function( $ ) {

        var imgArr = Array('<?php bloginfo('template_url'); ?>/img/icn_aaa_1.png', '<?php bloginfo('template_url'); ?>/img/icn_aaa_2.png', '<?php bloginfo('template_url'); ?>/img/icn_aaa_3.png')
        initFTS({
        img: imgArr,
        button: '#imgButton'
        });

        $('#imgButton').click(function(){
        fontUp();
        });
        });

    </script>
                                   
	<style>
	  
    .row-fluid.slideshow {

      width:960px;
    }
    .span12.hero.bottom-bump {
      position:absolute;
      width:960px;
    }
    .nivo-html-caption {
      display:none!important;
    }
    #nivoslider-112-caption-0 {
      display:inline;
    }
    .nivo-html-caption {
       position:absolute;
       top:0px;
       right:0px;
       width: 48.93617021276595%!important;
       
    }
    .nivo-caption {       
      right:15px!important;
       width: 45.93617021276595%!important;
}
    .nivo-caption p {
      font-size:20px!important;
      line-height:28px;
    }
    h1.crimson {
      background-color: none;
    }
    .nivo-caption a.cta {
      font-weight:bold;
      display:block!important;
      margin-top:10px;
    }
    .nivo-controlNav {float:none;margin: 0px!important;position:absolute;top:10px;left:780px}
    .nivo-controlNav a { padding:2px 5px 2px 5px; color:#ccc; border:solid 2px;margin-left:10px!important;font-family:tahoma;font-weight:bold;font-size:14px;background-color: rgba(255, 255, 255, .8);}
     .nivo-controlNav a.active, .nivo-controlNav a:hover {color:#f05231;text-decoration:none;}
    .nivo-imageLink {}
	</style>
			<?php if (is_page('Interactive Hearing Test')) { ?>
      <base href="<?php bloginfo('wpurl');?>" />
      <?php }; ?>
    <?php wp_head(); ?>

  </head>
  <body>
	<header class="container" >
		<div class="row-fluid">
			<div class="span5">
		
			  <a href="<?php bloginfo('url'); ?>" alt="<?php 
			  bloginfo('description'); ?>"><img src="<?php bloginfo('template_url'); ?>/img/hearingaids_com_logo_370x60.png" width="370" height="60" alt="Hearingaids Com Logo 370x60" class="logo"></a>
			</div><!--/.span12-->
			<div class="span4 offset3" style="">
			  	  <img src="<?php bloginfo('template_url'); ?>/img/btn_aaa_52x30.png" width="52" height="30" alt="Btn Aaa 52x30" style="vertical-align:top; border-right:1px solid #ccc;padding:5px 10px 5px 5px" class="element1" id="imgButton">
			  	  <div style="margin-top:5px; display:inline-block;">
			  <span class='st_facebook_large' displayText='Facebook' ></span>
<span class='st_pinterest_large' displayText='Pinterest'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
<span class='st_email_large' displayText='Email'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
</div>
			</div><!--/.span3-->
		</div><!-- /row-fluid-->
	</header>
    <style>
      .current_page_item {
        
      }
    </style>
		<nav id="access" role="navigation" class="crimson container">
				<h3 class="assistive-text"><?php _e( 'Main menu', 'twentyeleven' ); ?></h3>
				<?php /* Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff. */ ?>
				<div class="skip-link"><a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to primary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to primary content', 'twentyeleven' ); ?></a></div>
				<div class="skip-link"><a class="assistive-text" href="#secondary" title="<?php esc_attr_e( 'Skip to secondary content', 'twentyeleven' ); ?>"><?php _e( 'Skip to secondary content', 'twentyeleven' ); ?></a></div>
				<?php /* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary location is the one used. If one isn't assigned, the menu with the lowest ID is used. */ ?>
				<?php wp_nav_menu( array( 'theme_location' => 'HearingAids','menu' => 'header_menu','menu_class'  => 'menu container' ) ); ?>
			</nav><!-- #access -->
	
<?php
if ( is_front_page() ) {?>
<?} else {?>
<div class="container">
	<div class="row-fluid hero1">
		<div class="span12 breadcrumbs">
		  <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
		</div>
</div>
<?}
?>