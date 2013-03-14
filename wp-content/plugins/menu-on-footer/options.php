<?php
$location = $footer_options_page; // Form Action URI
$css_file='../wp-content/plugins/menu-on-footer/menu_on_footer.css'; //CSS

if ('process' == $_POST['stage']) 
{
    update_option('menu_footer_cat_exclude', $_POST['menu_footer_cat_exclude']);
    update_option('menu_footer_page_exclude', $_POST['menu_footer_page_exclude']);
	update_option('use_like', $_POST['use_like']);
	update_option('cat_switch', $_POST['cat_switch']);
	update_option('page_switch', $_POST['page_switch']);
	update_option('cat_depth', $_POST['cat_depth']);
	update_option('page_depth', $_POST['page_depth']);
	update_option('cat_orderby', $_POST['cat_orderby']);
	
	$newcontent= stripslashes($_POST['css']);
	
	$status = "Settings updated successfully.";


if (is_writeable($css_file)) 
{
	$f = fopen($css_file, 'w+');
    fwrite($f, $newcontent);
    fclose($f);
} else $error .= "The CSS file can not be read or written. Please, check your CHMOD(777).";
}
?>

<div class="wrap">
  <h2><?php _e('Menu On Footer Options', 'menu-on-footer') ?></h2>
  <?php if(isset($status)) {?>
  	<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
  		<p><?php echo $status;?></p>
	</div>
  <?php } ?>
  
  <?php if(isset($error)) {?>
  	<div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
  		<p><font color=red><?php echo $error;?></font></p>
	</div>
  <?php } ?>

  <form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
	<input type="hidden" name="stage" value="process" />
	 <table width="100%" cellspacing="2" cellpadding="5" class="form-table">

	 
	 	
		<tr valign="baseline">
         <th scope="row"><?php _e('Use like', 'menu-on-footer') ?></th> 
         <td>Plugin:<input type="radio" name="use_like" id="use_like" value="plugin"  <?php if ( get_option('use_like')=='plugin' ) echo 'checked="checked" '; ?> />
		 Widget: <input type="radio" name="use_like" id="use_like" value="widget"  <?php if ( get_option('use_like')=='widget' ) echo 'checked="checked" '; ?> />
		 </td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><h3><?php _e('Categories', 'menu-on-footer') ?></h3></th> 
         <td></td>
        </tr>
	 
	 <tr valign="baseline">
         <th scope="row"><?php _e('Show', 'menu-on-footer') ?></th> 
         <td><input type="checkbox" name="cat_switch" id="cat_switch" value="show"  <?php if ( get_option('cat_switch')=='show' ) echo 'checked="checked" '; ?> /></td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><?php _e('OrderBy', 'menu-on-footer') ?></th> 
         <td><input type="text" size="10" name="cat_orderby" id="cat_orderby" value="<?php echo get_option('cat_orderby'); ?>" /> <em>'id', 'name'(default), 'slug', 'count', 'term_group'</em> - <a href="http://codex.wordpress.org/Template_Tags/wp_list_categories" target="_blank">infos</a></td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><?php _e('Depth', 'menu-on-footer') ?></th> 
         <td><input type="text" size="2" name="cat_depth" id="cat_depth" value="<?php echo get_option('cat_depth'); ?>" /> <em>'-1', '0'(default), '1', '2', '3', ...</em> - <a href="http://codex.wordpress.org/Template_Tags/wp_list_categories" target="_blank">infos</a></td>
        </tr>
		
        <tr valign="baseline">
         <th scope="row"><?php _e('Exclude', 'menu-on-footer') ?></th> 
         <td><input type="text" name="menu_footer_cat_exclude" id="menu_footer_cat_exclude" value="<?php echo get_option('menu_footer_cat_exclude'); ?>" /> (separated by coma)</td>
        </tr>

		
		<tr valign="baseline">
         <th scope="row"><h3><?php _e('Pages', 'menu-on-footer') ?></h3></th> 
         <td></td>
        </tr>
		
		<tr valign="baseline">

         <th scope="row"><?php _e('Show', 'menu-on-footer') ?></th> 
         <td><input type="checkbox" name="page_switch" id="page_switch" value="show"  <?php if ( get_option('page_switch')=='show' ) echo 'checked="checked" '; ?> /></td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><?php _e('Depth', 'menu-on-footer') ?></th> 
         <td><input type="text" size="2" name="page_depth" id="page_depth" value="<?php echo get_option('page_depth'); ?>" /> <em>'-1', '0'(default), '1', '2', '3', ...</em> - <a href="http://codex.wordpress.org/Function_Reference/wp_list_pages" target="_blank">infos</a></td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><?php _e('Exclude', 'menu-on-footer') ?></th> 
         <td><input type="text" name="menu_footer_page_exclude" id="menu_footer_page_exclude" value="<?php echo get_option('menu_footer_page_exclude'); ?>" /> (separated by coma)</td>
        </tr>
		
		<tr valign="baseline">
         <th scope="row"><h3><?php _e('Edit style', 'menu-on-footer') ?></h3></th> 
         <td></td>
        </tr>
		
<?php
if (is_writeable($css_file)) 
{
	$f = fopen($css_file, 'r');
	$content = fread($f, filesize($css_file));
	$content = htmlspecialchars( $content );
} else $content = 'The CSS file can not be read or written. Please, check your CHMOD(777).';
?>
		
		<tr valign="baseline">
         <th scope="row"><?php _e('menu_on_footer.css', 'menu-on-footer') ?></th> 
         <td><textarea name="css" id="css" cols="100" rows="22" ><?php echo $content; ?></textarea></td>
        </tr>
		
     </table>


	<p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes', 'menu-on-footer') ?>" />
    </p>
  </form>
   
</div>