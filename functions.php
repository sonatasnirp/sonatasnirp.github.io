<?php 
/*-----------------------------------------------------------------------------------

	Here we have all the custom functions for the theme
	Please be extremely cautious editing this file,
	When things go wrong, they tend to go wrong in a big way.
	You have been warned!
	
	//  FUNCTIONS GUIDE

     1. Set Max Content Width
     2. Default Theme Constant
     3. No Header/Footer Globals
     4. WP Admin Bar
     5. Theme Setup
     6. Custom Login Logo
     7. Google Fonts
     8. JS Scripts
     9. CSS Scripts
    10. Widgets Setup
    11. Common Fix
    12. Excerpt Length
    13. Navigation Setup
    14. Custom Output Page Title ( Archives, Search, etc. )
    15. Custom Navigation Menu Walker
    16. Enable SVG on Wordpress Media Uploader
    17. Fixes HTTPS issue
    18. Tracking Code
    19. Previous and Next post in same Taxonomy
    20. Required Files for Framework
    21. Aq Resize Library
    22. Extend Visual Composer 
    23. Plugins Activation
    24. Mobile Detect Library
    25. Custom Styles ( CSS, Fonts and Colors )
    26. Social Metadata

-------------------------------------------------------------------------------------*/


/* 1. Set Max Content Width
-------------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) )
	$content_width = 1170;

/* 2. Default Theme Constant
-------------------------------------------------------------------------------------*/
define('AZ_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/framework/');
$options_alice = get_option('alice');


/* 3. No Header/Footer Global
-------------------------------------------------------------------------------------*/
$az_options_show_header = true;
$az_options_show_footer = true;


/* 4. WP Admin Bar - Enqueue Admin Style
-------------------------------------------------------------------------------------*/
function az_admin_scripts() {
	wp_enqueue_style( 'az-admin', get_template_directory_uri() . '/_include/css/admin-style.css' );
}
add_action( 'admin_enqueue_scripts', 'az_admin_scripts' );

// WP Admin Customization based on the theme layout
if ( ! function_exists( 'az_custom_admin_bar' ) ) {
	function az_custom_admin_bar() {
		echo '
		<style type="text/css">
			html, * html body { margin-top: 0 !important; }
		</style>';
	}
}
add_action( 'wp_head', 'az_custom_admin_bar', 99 );


/* 5. Theme Setup
-------------------------------------------------------------------------------------*/
if ( !function_exists( 'az_theme_setup' ) ) {
	function az_theme_setup(){
		global $options_alice;

		// Load Translation Domain
		load_theme_textdomain('az_alice', get_template_directory() . '/languages');

		// Register Menus
		register_nav_menus(array('primary_menu' => __('Primary Menu', 'az_alice') ));

		// Add RSS Feed links to HTML
		add_theme_support('automatic-feed-links');

		// Enable excerpts for pages
		add_post_type_support( 'page', 'excerpt' );

		// Configure Thumbnails
		add_theme_support('post-thumbnails');

		// WP 4.1 title tag
		add_theme_support( 'title-tag' );

		// Social meta
		if( !empty($options_alice['global_menu_share_button']) && $options_alice['global_menu_share_button'] == 'enable') {
			add_filter( 'wp_head', 'az_social_meta', 2 );
		}

		// Remove Emoji's
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

	}
}
add_action('after_setup_theme', 'az_theme_setup');


/* 6. Custom Login Logo
-------------------------------------------------------------------------------------*/
if ( !function_exists( 'az_custom_login_logo' ) ) {
	function az_custom_login_logo() {
		global $options_alice;
		$custom_logo = "";
		if (isset($options_alice['custom_admin_logo']['url'])) {
			$custom_logo = $options_alice['custom_admin_logo']['url'];
		}
		if ($custom_logo) {	
			echo '
		    <style type="text/css">
		        .login h1 a { background-image:url('. $custom_logo .') !important; height: 98px !important; width: auto !important; background-size: auto auto !important; }
		    </style>';
		} else {
			echo '
		    <style type="text/css">
		        .login h1 a { background-image:url('. get_template_directory_uri() .'/_include/img/logo-admin.png) !important; height: 98px !important; width: auto !important; background-size: auto auto !important; }
		    </style>';
		}
	}
}
add_action('login_enqueue_scripts', 'az_custom_login_logo');

if ( !function_exists( 'az_wp_login_url' ) ) {
	function az_wp_login_url() {
		return home_url();
	}
}
add_filter('login_headerurl', 'az_wp_login_url');

if ( !function_exists( 'az_wp_login_title' ) ) {
	function az_wp_login_title() {
		return get_option('blogname');
	}
}
add_filter('login_headertitle', 'az_wp_login_title');


/* 7. Google Fonts - Register / Enqueue
-------------------------------------------------------------------------------------*/
if ( !function_exists( 'az_google_fonts' ) ) {
	function az_google_fonts() {
		$protocol = is_ssl() ? 'https' : 'http';
		wp_enqueue_style( 'az-google-font', "$protocol://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,600,600italic,700italic,700|Montserrat:400,700|Crimson+Text:400,400italic" );
	}
}
add_action( 'wp_enqueue_scripts', 'az_google_fonts' );


/* 8. JS Scripts - Register / Enqueue
-------------------------------------------------------------------------------------*/
if ( !function_exists( 'az_register_js' ) ) {
	function az_register_js() {	
		global $options_alice;

		if (!is_admin()) {

			// Handle comments script
			if ( is_single() && comments_open() && get_option( 'thread_comments') ) {
				wp_enqueue_script( 'comment-reply' );
			} else {
				wp_dequeue_script( 'comment-reply' );
			}

			// Register Scripts
			wp_register_script('plugins', get_template_directory_uri() . '/_include/js/plugins.js', 'jquery', '1.0.0', TRUE);
			wp_register_script('main', get_template_directory_uri() . '/_include/js/main.js', 'jquery', '1.0.0', TRUE);
			wp_register_script('main-minify', get_template_directory_uri() . '/_include/js/main.min.js', 'jquery', '1.0.0', TRUE);

			// Enqueue Scripts
			wp_enqueue_script('jquery');
			wp_enqueue_script('plugins');

			// Minify
			if( $options_alice['performance_minified_settings'] == 1 ){

				wp_enqueue_script('main-minify');

				// Pass useful variables to the theme scripts through the following function
				wp_localize_script(
					'main-minify', 
					'theme_objects',
					array(
						'base' => get_template_directory_uri()
					)
				);

			} else {

				wp_enqueue_script('main');

				// Pass useful variables to the theme scripts through the following function
				wp_localize_script(
					'main', 
					'theme_objects',
					array(
						'base' => get_template_directory_uri()
					)
				);

			}

		}
	}
}
add_action('wp_enqueue_scripts', 'az_register_js');


/* 9. CSS Scripts - Register / Enqueue
-------------------------------------------------------------------------------------*/
if ( !function_exists( 'az_main_styles' ) ) {
	function az_main_styles() {		
		global $options_alice;

		// Register 
		wp_register_style('bootstrap', get_template_directory_uri() . '/_include/css/bootstrap.min.css');
		wp_register_style('main-fonts', get_template_directory_uri() . '/_include/css/fonts.css');
		wp_register_style('main-styles', get_stylesheet_directory_uri() . '/style.css');
		wp_register_style('main-styles-minify', get_stylesheet_directory_uri() . '/style.min.css');
			 
		// Enqueue
		wp_enqueue_style('bootstrap');
		wp_enqueue_style('main-fonts');

		// Minify
		if( $options_alice['performance_minified_settings'] == 1 ){

			wp_enqueue_style('main-styles-minify');

		} else {

			wp_enqueue_style('main-styles');

		}
	}
}
add_action('wp_enqueue_scripts', 'az_main_styles');


/* 10. Widgets Setup
-------------------------------------------------------------------------------------*/
if(function_exists('register_sidebar')) {
	function az_theme_slug_widgets_init(){
		register_sidebar(array(
			'name' => __('Footer Area 1', 'az_alice'), 
			'description' => __('Widget area for footer area.', 'az_alice'),
			'id' => 'footer-area-one', 
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>', 
			'before_title'  => '<h3>', 
			'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(array(
			'name' => __('Footer Area 2', 'az_alice'), 
			'description' => __('Widget area for footer area.', 'az_alice'),
			'id' => 'footer-area-two',  
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>', 
			'before_title'  => '<h3>', 
			'after_title'   => '</h3>'
			)
		);
		
		register_sidebar(array(
			'name' => __('Footer Area 3', 'az_alice'), 
			'description' => __('Widget area for footer area.', 'az_alice'),
			'id' => 'footer-area-three',  
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>', 
			'before_title'  => '<h3>', 
			'after_title'   => '</h3>'
			)
		);

		register_sidebar(array(
			'name' => __('Footer Area 4', 'az_alice'), 
			'description' => __('Widget area for footer area.', 'az_alice'),
			'id' => 'footer-area-four',  
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>', 
			'before_title'  => '<h3>', 
			'after_title'   => '</h3>'
			)
		);
	}
}
add_action( 'widgets_init', 'az_theme_slug_widgets_init' );


/* 11. Common Fix
-------------------------------------------------------------------------------------*/
// Twitter FIlter
function TwitterFilter( $string ) {

	$content_array = explode( " ", $string );
	$output = '';

	foreach ( $content_array as $content ) {

		if ( substr( $content, 0, 7 ) == "http://" ) {
			$content = '<a href="' . $content . '">' . $content . '</a>';
		}

		//starts with www.
		if ( substr( $content, 0, 4 ) == "www." ) {
			$content = '<a href="http://' . $content . '">' . $content . '</a>';
		}

		if ( substr( $content, 0, 8 ) == "https://" ) {
			$content = '<a href="' . $content . '">' . $content . '</a>';
		}

		if ( substr( $content, 0, 1 ) == "#" ) {
			$content = '<a href="https://twitter.com/search?src=hash&q=' . $content . '">' . $content . '</a>';
		}

		if ( substr( $content, 0, 1 ) == "@" ) {
			$content = '<a href="https://twitter.com/' . $content . '">' . $content . '</a>';
		}

		$output .= " " . $content;

	}

	$output = trim( $output );

	return $output;

}

function attr($s,$attrname) { // return html attribute
	preg_match_all('#\s*('.$attrname.')\s*=\s*["|\']([^"\']*)["|\']\s*#i', $s, $x);
	if (count($x)>=3) return $x[2][0]; else return "";
}


/* 12. Excerpt Lenght
-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'az_excerptlength_post' ) ) {
	function az_excerptlength_post($length) {
	    return 15;
	}
}

if ( ! function_exists( 'az_excerptmore' ) ) {
	function az_excerptmore($more) {
	    return ' ...';
	}
}

if ( ! function_exists( 'az_excerpt' ) ) {	
	function az_excerpt( $length_callback = '', $more_callback = 'az_excerptmore' ) {

	    global $post;
		
	    if ( function_exists( $length_callback ) ) {
			add_filter( 'excerpt_length', $length_callback );
	    }
		
	    if ( function_exists( $more_callback ) ){
			add_filter( 'excerpt_more', $more_callback );
	    }
		
	    $output = get_the_excerpt();
	    $output = apply_filters( 'wptexturize', $output );
	    $output = apply_filters( 'convert_chars', $output );
	    $output = $output;
		
	    return $output;
	}   
}


/* 13. Navigation Setup
-------------------------------------------------------------------------------------*/

// Simple Navigation Blog
if ( ! function_exists( 'az_normal_pagination' ) ) {

	function az_normal_pagination( $query = null ) {  

		if ( $query == null ) {
			global $wp_query;
			$query = $wp_query;
		}

		$page = $query->query_vars['paged'];
		$pages = $query->max_num_pages;

		if ( $page == 0 ) {
			$page = 1;
		}

		$output = '';

		if( $pages > 1 ) {
			$output .= '<div class="normal-pagination">';
			if ( $page + 1 <= $pages ) {
				$output .= '<div class="prev-post"><a href="' . get_pagenum_link( $page + 1 ) . '">' . '<div class="pagination-inner">' . __( 'Older Post' , 'az_alice' ) . '</div><span class="icon-arrow-pag font-icon-arrow-left-simple-thin-round"></span></a></div>';
			}
			if ( $page - 1 >= 1 ) {
				$output .= '<div class="next-post"><a href="' . get_pagenum_link( $page - 1 ) . '"><div class="pagination-inner">' . __( 'Newer Post' , 'az_alice' ) . '</div><span class="icon-arrow-pag font-icon-arrow-right-simple-thin-round"></span></a></div>';
			}
			$output .= '</div>';

		}
		return $output;
		 
	}

}

// Get Post Number
if ( ! function_exists( 'az_get_post_number' ) ) {
	function az_get_post_number( $postID ) {
		global $wp_query;
		$temp_query = $wp_query;
		$postNumberQuery = new WP_Query('orderby=date&posts_per_page=-1');
		$counter = 1;
		$postCount = 0;

		if($postNumberQuery->have_posts()) :
			while ($postNumberQuery->have_posts()) : $postNumberQuery->the_post();
				if ($postID == get_the_ID()){
					$postCount = $counter;
				} else {
					$counter++;
				}
		endwhile; endif;

		wp_reset_query();
		$wp_query = $temp_query;
		return $postCount;
	}
}

// Number Pagination
if ( ! function_exists( 'az_number_pagination' ) ) {

	function az_number_pagination( $query = null, $paginated = false, $range = 2, $echo = true ) {  

		if ( $query == null ) {
			global $wp_query;
			$query = $wp_query;
		}

		$page = $query->query_vars['paged'];
		$pages = $query->max_num_pages;

		if ( $page == 0 ) {
			$page = 1;
		}

		$output = '';
		if( $pages > 1 ) {
			$output .= '<div class="normal-pagination numbers-only">';

				for ( $i = 1; $i <= $pages; $i++ ) {

					if ( $i == 1 || $i == $pages || $i == $page || ( $i >= $page - $range && $i <= $page + $range ) ) {
						$output .= '<a href="' . get_pagenum_link( $i ) . '"' . ( $page == $i ? ' class="active"' : '' ) . '>' . $i . '</a>';
					} else if ( ( $i != 1 && $i == $page - $range - 1 ) || ( $i != $page && $i == $page + $range + 1 ) ) {
						$output .= '<a class="nothing-dot">...</a>';
					}

				}
				
			$output .= '</div>';
		}
		return $output;
	}
}


/* 14. Custom Output Page Title ( Archives, Search, etc. )
-------------------------------------------------------------------------------------*/
if( !function_exists( 'az_custom_get_page_title' ) ) {
    function az_custom_get_page_title() {
        $page_title = '';
        if( is_archive() ) {
                if( is_category() ) {
                    $page_title = sprintf( __( 'All Posts in &#8220;%s&#8221;', 'az_alice' ), single_cat_title('', false) );
                } elseif( is_tag() ) {
                    $page_title = sprintf( __( 'All Posts in &#8220;%s&#8221;', 'az_alice' ), single_tag_title('', false) );
                } elseif( is_date() ) {
                    if( is_month() ) {
                        $page_title = sprintf( __( 'Archive for &#8220;%s&#8221;', 'az_alice' ), get_the_time( 'F, Y' ) );
                    } elseif( is_year() ) {
                        $page_title = sprintf( __( 'Archive for &#8220;%s&#8221;', 'az_alice' ), get_the_time( 'Y' ) );
                    } elseif( is_day() ) {
                        $page_title = sprintf( __('Archive for &#8220;%s&#8221;', 'az_alice' ), get_the_time( get_option('date_format') ) );
                    } else {
                        $page_title = __('Blog Archives', 'az_alice');
                    }
                } elseif( is_author() ) {
                    if(get_query_var('author_name')) {
                        $curauth = get_user_by( 'login', get_query_var('author_name') );
                    } else {
                        $curauth = get_userdata(get_query_var('author'));
                    }
                    $page_title = sprintf(__('All Posts By &#8220;%s&#8221;', 'az_alice'), $curauth->display_name);
                } 
            } 
		elseif( is_search() ) {
       		$page_title = sprintf( __( 'Search Results for &#8220;%s&#8221;', 'az_alice' ), get_search_query() );
        }

        return $page_title;
    }
}


/* 15. Custom Navigation Menu Walker
-------------------------------------------------------------------------------------*/
class az_Nav_Walker extends Walker_Nav_Menu {

    function start_lvl( &$output, $depth=0, $args=array() ) {
    	if ( $depth == 0 ) {
        	$output .= '<ul class="sub-menu">';
    	} else if ( $depth == 1 ) {
        	$output .= '<ul class="sub-menu">';
    	}
    }

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){

        $id_field = $this->db_fields['id'];

        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }

        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

    }

    function start_el( &$output, $object, $depth=0, $args=array(), $current_object_id=0 ) {

        global $wp_query;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $new_output = '';
        $depth_class = ( $args->has_children ? 'has-ul ' : '' );

        $class_names = $value = $selected_class = '';
        $classes = empty( $object->classes ) ? array() : ( array ) $object->classes;

        $current_indicators = array('current-menu-item', 'current-menu-parent', 'current_page_item', 'current-menu-ancestor');

        foreach ( $classes as $el ) {
            if ( in_array( $el, $current_indicators ) ) {
                $selected_class = 'current selected active ';
            }
        }

        $class_names = ' class="' . $selected_class . $depth_class . 'menu-item' . ( ! empty( $classes[0] ) ? ' ' . $classes[0] : '' ) . '"';

        if ( ! get_post_meta( $object->object_id , '_members_only' , true ) || is_user_logged_in() ) {
            $output .= $indent . '<li id="menu-item-'. $object->ID . '"' . $class_names . '>';
        }

        $attributes  = ! empty( $object->attr_title ) ? ' title="'  . esc_attr( $object->attr_title ) .'"' : '';
        $attributes .= ! empty( $object->target )     ? ' target="' . esc_attr( $object->target     ) .'"' : '';
        $attributes .= ! empty( $object->xfn )        ? ' rel="'    . esc_attr( $object->xfn        ) .'"' : '';
        $attributes .= ! empty( $object->url )        ? ' href="'   . esc_attr( $object->url        ) .'"' : '';

        $object_output = $args->before;
        $object_output .= '<a' . $attributes . '>';
        $object_output .= $args->link_before . apply_filters( 'the_title', $object->title, $object->ID ) . $args->link_after;
        $object_output .= '</a>';
        $object_output .= $args->after;

        if ( !get_post_meta( $object->object_id, '_members_only' , true ) || is_user_logged_in() ) {

            $output .= apply_filters( 'walker_nav_menu_start_el', $object_output, $object, $depth, $args );

        }

        $output .= $new_output;

    }

    function end_el(&$output, $object, $depth=0, $args=array()) {

        if ( !get_post_meta( $object->object_id, '_members_only' , true ) || is_user_logged_in() ) {
            $output .= "</li>\n";
        }

    }
    
    function end_lvl(&$output, $depth=0, $args=array()) {

        $output .= "</ul>\n";

    }

}


/* 16. Enable SVG on Wordpress Media Uploader
-------------------------------------------------------------------------------------*/
// If you want you can use this function for enabled the SVG file for Media Uploader
function az_cc_mime_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'az_cc_mime_types' );


/* 17. Fixes HTTPS issues with wp_get_attachment_url()
-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'az_ssl_for_attachments' ) ) {
	function az_ssl_for_attachments($url) {
		$http = site_url(FALSE, 'http');
		$https = site_url(FALSE, 'https');
		$isSecure = false;
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
		|| $_SERVER['SERVER_PORT'] == 443) {
			$isSecure = true;
		}
		return ( $isSecure ) ? str_replace($http, $https, $url) : $url;
	}
}
add_filter('wp_get_attachment_url', 'az_ssl_for_attachments');


/* 18. Tracking Code
-------------------------------------------------------------------------------------*/
// Tracking Code
if( !empty($options_alice['tracking_code']) ){

	if ( !function_exists('az_site_tracking_header') ) {	
		function az_site_tracking_header() {
			global $options_alice;

			$tracking = esc_html($options_alice['tracking_code']);
			if ( $tracking !== '' ) {
				echo "
<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '$tracking', 'auto');
ga('send', 'pageview');

</script>
<!-- End Google Analytics -->
				";
			}
		}
	}
	add_action('wp_head', 'az_site_tracking_header');
}

/* 19. Previous and Next Post in Same Taxonomy - Author: Bill Erickson
-------------------------------------------------------------------------------------*/
function be_get_previous_post($in_same_cat = false, $excluded_categories = '', $taxonomy = 'category') {
	return be_get_adjacent_post($in_same_cat, $excluded_categories, true, $taxonomy);
}

function be_get_next_post($in_same_cat = false, $excluded_categories = '', $taxonomy = 'category') {
	return be_get_adjacent_post($in_same_cat, $excluded_categories, false, $taxonomy);
}

function be_get_adjacent_post( $in_same_cat = false, $excluded_categories = '', $previous = true, $taxonomy = 'category' ) {
	global $post, $wpdb;
	
	if ( ( ! $post = get_post() ) || ! taxonomy_exists( $taxonomy ) ) 
		return null; 

	$current_post_date = $post->post_date;

	$join = '';
	$posts_in_ex_cats_sql = '';

	if ( $in_same_cat || ! empty( $excluded_categories ) ) {
		$join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

		if ( $in_same_cat ) {
			if ( ! is_object_in_taxonomy( $post->post_type, $taxonomy ) ) 
				return '';
			$cat_array = wp_get_object_terms($post->ID, $taxonomy, array( 'fields' => 'ids' ) );
			$join .= $wpdb->prepare( " AND tt.taxonomy = %s AND tt.term_id IN (" . implode( ',', array_map( 'intval', $cat_array ) ) . ")", $taxonomy ); 
		}

		$posts_in_ex_cats_sql = $wpdb->prepare( "AND tt.taxonomy = %s", $taxonomy );
		if ( ! empty( $excluded_categories ) ) {
			if ( ! is_array( $excluded_categories ) ) {
				if ( false !== strpos( $excluded_categories, ' and ' ) ) { 
					_deprecated_argument( __FUNCTION__, '3.3', sprintf( __( 'Use commas instead of %s to separate excluded terms.', 'az_alice' ), "'and'" ) ); 
					$excluded_categories = explode( ' and ', $excluded_categories );
				} else {
					$excluded_categories = explode( ',', $excluded_categories );
				}
			}

			$excluded_categories = array_map( 'intval', $excluded_categories );
				
			if ( ! empty( $cat_array ) ) {
				$excluded_categories = array_diff( $excluded_categories, $cat_array );
				$posts_in_ex_cats_sql = '';
			}

			if ( !empty($excluded_categories) ) {
				$posts_in_ex_cats_sql = $wpdb->prepare( " AND tt.taxonomy = %s AND tt.term_id NOT IN (" . implode( $excluded_categories, ',' ) . ')', $taxonomy ); 
			}
		}
	}

	$adjacent = $previous ? 'previous' : 'next';
	$op = $previous ? '<' : '>';
	$order = $previous ? 'DESC' : 'ASC';

	$join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
	$where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = %s AND p.post_excerpt NOT like 'excludePost' AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type), $in_same_cat, $excluded_categories );
	$sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

	$query = "SELECT p.ID FROM $wpdb->posts AS p $join $where $sort";

	$query_key = 'adjacent_post_' . md5( $query );
	$result = wp_cache_get($query_key, 'counts');
	if ( false !== $result ) {
		if( $result )
			$result = get_post( $result );
		return $result;
	}

	$result = $wpdb->get_var( $query );
	if (null === $result )
		$result = '';

	wp_cache_set( $query_key, $result, 'counts');

	if ( $result ) 
		$result = get_post( $result );

	return $result;
}


function be_previous_post_link($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '', $taxonomy = 'category') {
	be_adjacent_post_link($format, $link, $in_same_cat, $excluded_categories, true, $taxonomy);
}


function be_next_post_link($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '', $taxonomy = 'category') {
	be_adjacent_post_link($format, $link, $in_same_cat, $excluded_categories, false, $taxonomy);
}


function be_adjacent_post_link($format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true, $taxonomy = 'category') {
	if ( $previous && is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = be_get_adjacent_post($in_same_cat, $excluded_categories, $previous, $taxonomy);

	if ( !$post )
		return;

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = $previous ? __('Previous Post', 'az_alice') : __('Next Post', 'az_alice');

	$title = apply_filters('the_title', $title, $post->ID);
	$date = mysql2date(get_option('date_format'), $post->post_date);
	$rel = $previous ? 'prev' : 'next';

	$string = '<a href="'.get_permalink($post).'" title="'.$title.'">';
	$link = str_replace('%title', $title, $link);
	$link = str_replace('%date', $date, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link", $format, $link );
}


/* 20. Required Files for Framework
-------------------------------------------------------------------------------------*/
function az_redux_error_notice() {
    echo '<div class="error notice">';
    echo '<p>' . __( 'You need install and activate the <strong>Redux Framework</strong> plugin for this theme.', 'az_alice' ) . '</p>';
    echo '</div>';
}

if ( ! class_exists( 'ReduxFramework' ) ) {
	add_action( 'admin_notices', 'az_redux_error_notice' );
}

$tempdir = get_template_directory();

require_once($tempdir .'/framework/redux/loader.php');
require_once($tempdir .'/framework/redux/meta-config.php');
require_once($tempdir .'/framework/redux/redux-core/az_framework/config.php');

require_once($tempdir .'/framework/redux/custom-functions-meta.php');


/* 21. Aq Resize Library
-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'aq_resize' ) ) {
	include($tempdir .'/framework/redux/aq_resizer.php' );
}


/* 22. Extend Visual Composer
-------------------------------------------------------------------------------------*/
require_once($tempdir .'/vc_extend/extend-vc.php');


/* 23. Plugin Activation
-------------------------------------------------------------------------------------*/
require_once($tempdir .'/framework/plugins-activation/init.php');


/* 24. Mobile Detect Library
-------------------------------------------------------------------------------------*/
require_once($tempdir .'/framework/redux/Mobile_Detect.php');


/* 25. Custom Styles ( CSS, Fonts and Colors )
-------------------------------------------------------------------------------------*/
require_once($tempdir .'/_include/custom-styles.php');


/* 26. Social Metadata
-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'az_social_meta' ) ) {
	function az_social_meta() {
		global $post, $options_alice;

		$themes = wp_get_themes();

		if ( is_singular() ) {
			// If we are on a blog post/page
	        echo "<meta property='og:title' content='".get_the_title()."'/>\n";
	        echo "<meta property='og:type' content='article'/>\n";
	        echo "<meta property='og:url' content='".get_permalink()."'/>\n";
	        echo "<meta property='og:site_name' content='".get_bloginfo('name')."'/>\n";
	        
	        if ( has_excerpt( $post->ID ) ) {
			    echo "<meta property='og:description' content='".wp_strip_all_tags(az_excerpt('az_excerptlength_post'))."' />\n";
			} else {
			    echo "<meta property='og:description' content='".get_bloginfo('description')."' />\n";
			}

			if ( has_post_thumbnail( $post->ID ) ) {
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_image_src( $thumb, 'full' );
				$img_url = $img_url[0];
			} else {
				$img_url = $options_alice['logo']['url'];
			}

			echo "<meta property='og:image' content='".$img_url."' />\n";

		} elseif(is_front_page() or is_home()) {

			echo "<meta property='og:title' content='".get_bloginfo("name")."'/>\n";
	        echo "<meta property='og:type' content='website'/>\n";
	        echo "<meta property='og:url' content='".get_permalink()."'/>\n";
	        echo "<meta property='og:site_name' content='".get_bloginfo('name')."'/>\n";

	        if ( has_excerpt( $post->ID ) ) {
			    echo "<meta property='og:description' content='".wp_strip_all_tags(az_excerpt('az_excerptlength_post'))."' />\n";
			} else {
			    echo "<meta property='og:description' content='".get_bloginfo('description')."' />\n";
			}

			if ( has_post_thumbnail( $post->ID ) ) {
				$thumb = get_post_thumbnail_id();
				$img_url = wp_get_attachment_image_src( $thumb, 'full' );
				$img_url = $img_url[0];
			} else {
				$img_url = $options_alice['logo']['url'];
			}

			echo "<meta property='og:image' content='".$img_url."' />\n";

		}

	}
}

?>