<?php 
// Register Navigation Menus
function custom_navigation_menus() {
	$locations = array(
		'header_menu' => __( 'Custom Header Menu', 'HearingAids' ),
		'sidebar_menu' => __( 'Custom Sidebar Menu', 'HearingAids' ),
		'footer_menu' => __( 'Custom Footer Menu', 'HearingAids' ),
	);

	register_nav_menus( $locations );
}

// Hook into the 'init' action
add_action( 'init', 'custom_navigation_menus' );

// Register Sidebar
function sidebars()  {
	$args = array(
		'id'            => 'footer_sidebar',
		'name'          => 'Footer About Section',
		'description'   => 'Custom About Section for the footer',
		'before_title'  => ' <h5 class="brown">',
		'after_title'   => '</h5>',
		'before_widget' => '<div class="span12"><div class="about twopercent">',
		'after_widget'  => '</div></div>',
	);

	register_sidebar( $args );

	$sidebar1 = array(
		'id'            => 'sidebar1',
		'name'          => 'Sidebar One',
		'description'   => 'Sidebar for Facts page',
		'before_title'  => ' <h5 class="cyan underline-grey">',
		'before_widget' => '<aside id="%1$s" class="widget %2$s fourpercent bottom-bump">',
		'after_widget' => "</aside>",
		'after_title'   => '</h5>',
	);

	register_sidebar( $sidebar1 );

	$sidebar2 = array(
		'id'            => 'sidebar2',
		'name'          => 'Sidebar Two',
		'description'   => 'Sidebar for Quiz page',
		'before_title'  => ' <h5 class="cyan underline-grey">',
		'before_widget' => '<aside id="%1$s" class="widget %2$s fourpercent bottom-bump">',
		'after_widget' => "</aside>",
		'after_title'   => '</h5>',

	);
	register_sidebar( $sidebar2 );
	
	
	$sidenav = array(
		'id'            => 'sidenav',
		'name'          => 'Sidenav',
		'description'   => 'Sidebar area for navigation',
		'before_title'  => ' <h5 class="white">',
		'after_title'   => '</h5>',
		'before_widget' => '<aside id="dc_jqaccordion_widget-2" class="widget bottom-bump">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $sidenav );

	$partners = array(
		'id'            => 'Partners',
		'name'          => 'Partners',
		'description'   => 'Footer widget for Section Landing Page template',
		'before_title'  => ' <h4 class="cyan inline">',
		'after_title'   => '</h4>',
		'before_widget' => '',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $partners );

	$legal = array(
		'id'            => 'legal',
		'name'          => 'Copyright Footer',
		'description'   => 'Footer Sidebar for copyright, and legal links',
		'before_title'  => '',
		'after_title'   => '',
		'before_widget' => '',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $legal );

}

// Hook into the 'widgets_init' action
add_action( 'widgets_init', 'sidebars' );

function dimox_breadcrumbs() {

	/* === OPTIONS === */
	$text['home']     = 'Home'; // text for the 'Home' link
	$text['category'] = 'Archive by Category "%s"'; // text for a category page
	$text['search']   = 'Search Results for "%s" Query'; // text for a search results page
	$text['tag']      = 'Posts Tagged "%s"'; // text for a tag page
	$text['author']   = 'Articles Posted by %s'; // text for an author page
	$text['404']      = 'Page Not Found'; // text for the 404 page

	$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
	$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
	$delimiter   = ' <span class="divider">/ </span>'; // delimiter between crumbs
	$before      = '	<li class="active">'; // tag before the current crumb
	$after       = '</li>'; // tag after the current crumb
	/* === END OF OPTIONS === */

	global $post;
	$homeLink = get_bloginfo('url') . '/';
	$linkBefore = '<li typeof="v:Breadcrumb">';
	$linkAfter = '</li>';
	$linkAttr = ' rel="v:url" property="v:title"';
	$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

	if (is_home() || is_front_page()) {

		if ($showOnHome == 1) echo '<ul id="crumbs" class="breadcrumb"><a href="' . $homeLink . '">' . $text['home'] . '</a></ul>';

	} else {

		echo '<ul id="crumbs" class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf($link, $homeLink, $text['home']) . $delimiter;

		if ( is_category() ) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				$cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
			}
			echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

		} elseif ( is_search() ) {
			echo $before . sprintf($text['search'], get_search_query()) . $after;

		} elseif ( is_day() ) {
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
			echo $before . get_the_time('d') . $after;

		} elseif ( is_month() ) {
			echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
			echo $before . get_the_time('F') . $after;

		} elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;

		} elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
				if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$cats = get_category_parents($cat, TRUE, $delimiter);
				if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
				$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
				$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
				echo $cats;
				if ($showCurrent == 1) echo $before . get_the_title() . $after;
			}

		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;

		} elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			$cats = get_category_parents($cat, TRUE, $delimiter);
			$cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
			$cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
			echo $cats;
			printf($link, get_permalink($parent), $parent->post_title);
			if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

		} elseif ( is_page() && !$post->post_parent ) {
			if ($showCurrent == 1) echo $before . get_the_title() . $after;

		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo $breadcrumbs[$i];
				if ($i != count($breadcrumbs)-1) echo $delimiter;
			}
			if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;

		} elseif ( is_tag() ) {
			echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

		} elseif ( is_author() ) {
	 		global $author;
			$userdata = get_userdata($author);
			echo $before . sprintf($text['author'], $userdata->display_name) . $after;

		} elseif ( is_404() ) {
			echo $before . $text['404'] . $after;
		}

		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
			echo __('Page') . ' ' . get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
		}

		echo '</ul>';

	}
} // end dimox_breadcrumbs()

// Remove WordPress Auto P
remove_filter( 'the_content', 'wpautop' );


class Thumbnail_walker extends Walker_page {
        function start_el(&$output, $page, $depth, $args, $current_page) {
        if ( $depth )
            $indent = str_repeat("\t", $depth);
        else
            $indent = '';
 
        extract($args, EXTR_SKIP);
        $css_class = array('page_item', 'page-item-'.$page->ID);
        if ( !empty($current_page) ) {
            $_current_page = get_page( $current_page );
            _get_post_ancestors($_current_page);
            if ( isset($_current_page->ancestors) && in_array($page->ID, (array) $_current_page->ancestors) )
                $css_class[] = 'current_page_ancestor';
            if ( $page->ID == $current_page )
                $css_class[] = 'current_page_item';
            elseif ( $_current_page && $page->ID == $_current_page->post_parent )
                $css_class[] = 'current_page_parent';
        } elseif ( $page->ID == get_option('page_for_posts') ) {
            $css_class[] = 'current_page_parent';
        }
 
        $css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
 
        $output .= $indent . '<td class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '">' . $link_before . apply_filters( 'the_title', '' ) . $link_after . get_the_post_thumbnail($page->ID, array(72,72)) .'</a>';
 
        if ( !empty($show_date) ) {
            if ( 'modified' == $show_date )
                $time = $page->post_modified;
            else
                $time = $page->post_date;
 
            $output .= " " . mysql2date($date_format, $time);
        }
    }
}

function thumbnail_pages($args = '') {
        $defaults = array(
        'depth' => 0, 'show_date' => '',
        'date_format' => get_option('date_format'),
        'child_of' => 0, 'exclude' => '',
        'title_li' => __('Pages'), 'echo' => 1,
        'authors' => '', 'sort_column' => 'menu_order, post_title',
        'link_before' => '', 'link_after' => '', 'walker' => '',
    );
 
    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );
 
    $output = '';
    $current_page = 0;
 
    // sanitize, mostly to keep spaces out
    $r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);
 
    // Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)
    $exclude_array = ( $r['exclude'] ) ? explode(',', $r['exclude']) : array();
    $r['exclude'] = implode( ',', apply_filters('wp_list_pages_excludes', $exclude_array) );
 
    // Query pages.
    $r['hierarchical'] = 0;
    $pages = get_pages($r);
 
    if ( !empty($pages) ) {
        if ( $r['title_li'] )
            $output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';
 
        global $wp_query;
        if ( is_page() || is_attachment() || $wp_query->is_posts_page )
            $current_page = $wp_query->get_queried_object_id();
        $output .= walk_page_tree($pages, $r['depth'], $current_page, $r);
 
        if ( $r['title_li'] )
            $output .= '</ul></li>';
    }
 
    $output = apply_filters('wp_list_pages', $output, $r);
 
    if ( $r['echo'] )
        echo $output;
    else
        return $output;
}


?>