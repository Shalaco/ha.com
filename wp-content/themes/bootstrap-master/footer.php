    
    
    <footer class="bottom-bump give-me-some-space">
      <div class="container" style="margin-top:20px;margin-bottom:20px">
      <div class="row">
       	<?php dynamic_sidebar( 'footer_sidebar' ); ?>
      </div>
      
      <div class="row-fluid flush ">
      
<!--       
<table class="table">
  <tr>
<?php
//get all ancestor pages, pages with no post parent
global $wpdb;
$result = $wpdb->get_results("SELECT ID, post_title, guid FROM $wpdb->posts WHERE post_type = 'page' AND post_parent = 0 AND post_status = 'publish'");

//loop result from above database query and list child pages of ancestors
 $i = 1;
foreach ($result as $res){
if ($res->ID != 114) {
echo '<td class="span4 dashed"><ul>';
//this is the ancestors
echo "<li><a href='$res->guid' class='parent' >$res->post_title</a></li><ul>";

//prepare ancestor id for wp_list_pages
$ancestor_id = $res->ID;

//list all child and grand child of ancestor page
wp_list_pages("title_li=&child_of=$ancestor_id");

echo '</ul></li></td>';
if($i %3 == 0) {
  echo '</tr><tr>';
}
$i++; 
}
}

?>
</tr>
</table>-->


      
      </div><!--/span4 dashed-->
      <div class="span12 copyright container">
        <br/>
        <?php dynamic_sidebar( 'legal' ); ?>
      </div>
      </div><!-- /row-fluid-->
          <?php wp_footer(); ?>
     
    </div>
    </footer>
    <?php if ( is_user_logged_in() ) {
         echo '<pre>';

         global $post;  
         print_r($post);
         echo '</pre>';                                      
         echo '<pre>';
         $page_id = $wp_query->get_queried_object_id();
         echo get_post_meta( $page_id, '_wp_page_template', true ); 
         echo '</pre>';                                      
         } ?> 
    <!--share this-->
    <script type="text/javascript">var switchTo5x=true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher: "1982b8a2-7809-4d45-b050-0e7ed676b35b", onhover: false, doNotHash: true, doNotCopy: false, hashAddressBar: true});</script>
  </body>
</html>
