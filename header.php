<?php
global $az_options_show_header, $postid;
$options_alice = get_option('alice');

if ( class_exists( 'ReduxFramework' ) ) {
    $options_az = redux_post_meta( 'alice' , $postid );
}

// Animation Mobile Effects
$animation_scroll_effects = null;
if( !empty($options_alice['enable_mobile_scroll_animation_effects']) && $options_alice['enable_mobile_scroll_animation_effects'] == 1) { 
    $animation_scroll_effects = 'scroll-animation-effects-enabled';
} else { 
    $animation_scroll_effects = 'no-scroll-animation-effects';
}

// Disable Right Click Option
$right_click_options = null;
if( !empty($options_alice['disable_right_click']) && $options_alice['disable_right_click'] == 1) { 
    $right_click_options = 'right-click-block-enabled'; 
} else { 
    $right_click_options = 'right-click-block-disabled';
}

// Dark Logo, Navi and Title Header Texts
if ( is_home() || is_search() || is_404() ) { 
    $postid = get_option('page_for_posts');
}
else {
    $postid = $post->ID;
}

$type_navi_color = null;
if ( is_search() ) {

    $check_search_type_color_settings = $options_alice['search_logo_navi_type_color'];

    if ( $check_search_type_color_settings == "dark") {
        $type_navi_color = 'dark-type';
    } else {
        $type_navi_color = 'light-type';
    }

} else if ( is_archive() ) {

    $check_archive_type_color_settings = $options_alice['archive_logo_navi_type_color'];

    if ( $check_archive_type_color_settings == "dark") {
        $type_navi_color = 'dark-type';
    } else {
        $type_navi_color = 'light-type';
    }

} else if ( is_404() ) {

    $check_error_type_color_settings = $options_alice['error_logo_navi_type_color'];

    if ( $check_error_type_color_settings == "dark") {
        $type_navi_color = 'dark-type';
    } else {
        $type_navi_color = 'light-type';
    }

} else {

    if ( class_exists( 'ReduxFramework' ) ) {
        $check_type_color_settings = $options_az['az_logo_navi_type_color'];
    } else {
        $check_type_color_settings = 'light';
    }

    if ( $check_type_color_settings == "dark") {
        $type_navi_color = 'dark-type';
    } else {
        $type_navi_color = 'light-type';
    }

}


?>
<!DOCTYPE html>
<!--[if gte IE 9]>
<html class="no-js lt-ie9 no-scroll-animation-effects <?php echo esc_attr( $right_click_options ); ?>" <?php language_attributes(); ?>>
<![endif]-->
<html <?php language_attributes(); ?> class="no-js <?php echo esc_attr( $right_click_options ); ?> <?php echo esc_attr( $animation_scroll_effects ); ?>">
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php if (isset($options_alice['custom_ios_bookmark_title']) && $options_alice['custom_ios_bookmark_title'] != "") { $bookmark_title = esc_html($options_alice['custom_ios_bookmark_title']); ?>
<meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( $bookmark_title ); ?>">
<?php } ?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">

<!-- Mobile Internet Explorer ClearType Technology -->
<!--[if IEMobile]>  <meta http-equiv="cleartype" content="on"><![endif]-->

<!-- RSS - Pingbacks - Favicon -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php if (!empty($options_alice['favicon']['url'])) { ?>
<link rel="shortcut icon" href="<?php echo esc_url( $options_alice['favicon']['url'] ); ?>" />
<?php } ?>
<?php if (!empty($options_alice['custom_ios_144']['url'])) { ?>
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo esc_url( $options_alice['custom_ios_144']['url'] ); ?>" />
<?php } ?>
<?php if (!empty($options_alice['custom_ios_114']['url'])) { ?>
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url( $options_alice['custom_ios_114']['url'] ); ?>" />
<?php } ?>
<?php if (!empty($options_alice['custom_ios_72']['url'])) { ?>
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url( $options_alice['custom_ios_72']['url'] ); ?>" />
<?php } ?>
<?php if (!empty($options_alice['custom_ios_57']['url'])) { ?>
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo esc_url( $options_alice['custom_ios_57']['url'] ); ?>" />
<?php } ?>


<?php wp_head(); ?>

</head>

<!-- Body -->
<?php 

// Mobile Detect
$detect = new Mobile_Detect;
$device_version = '';
if ( $detect->isMobile() ) {
    $device_version = 'mobile-version';
} else {
    $device_version = 'desktop-version';
}

// Header Creative/Classic
if( !empty($options_alice['global_menu_type']) && $options_alice['global_menu_type'] == 'creative') {
    $header_class = 'creative-header';
} else {
    $header_class = 'classic-header';
}
?>

<body <?php body_class( array( $device_version, $header_class ) ); ?>>

<?php
if( !empty($options_alice['preloader_settings']) && $options_alice['preloader_settings'] == 1) {

    if ( is_home() ) { 
        az_preloader(get_option('page_for_posts')); 
    }
    else { 
        az_preloader($postid); 
    }

} ?>

<?php get_template_part('framework/core/header/az-header-menu'); ?>

<div class="wrap_all <?php echo esc_attr( $type_navi_color ); ?>">

<?php if($az_options_show_header) { /* Start $show_header; */ ?>

<header id="header">
    <?php get_template_part('framework/core/header/az-header-creative'); ?>
</header>

<?php if( !empty($options_alice['global_menu_share_button']) && $options_alice['global_menu_share_button'] == 'enable') { ?>
<!-- Start Modal Share -->
<?php 
    wp_reset_query();
    global $post;

    if ( isset( $post ) ) {
        $url = urlencode( get_permalink( $post->ID ) );
        $title = urlencode( get_the_title( $post->ID ) );
        $media = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
    } else {
        $url = esc_url( home_url() );
        $title = get_bloginfo( 'name' );
        $media = array('');
    }
?>
<div class="modal-container-share">
    <div class="header-share">
        <a class="close-modal-share">
            <div class="bars">
                <i class="bar top"></i>
                <i class="bar bottom"></i>
            </div>
        </a>
    </div>
    <div class="modal-share">
        <?php if( !empty($options_alice['header_twitter_share']) && $options_alice['header_twitter_share'] == 1) { ?>
        <a class="az-share-twitter share-btn-footer" href="https://twitter.com/home?status=<?php echo !empty( $title ) ? $title : ''; ?>+<?php echo !empty( $url ) ? $url : ''; ?>" title="<?php _e( 'Share on Twitter', 'az_alice' ); ?>" target="_blank"><i class="font-icon-social-twitter"></i><span>Share</span></a>
        <?php } ?>
        <?php if( !empty($options_alice['header_facebook_share']) && $options_alice['header_facebook_share'] == 1) { ?>
        <a class="az-share-facebook share-btn-footer" href="https://www.facebook.com/share.php?u=<?php echo !empty( $url ) ? $url : ''; ?>&amp;title=<?php echo !empty( $title ) ? $title : ''; ?>" title="<?php _e( 'Share on Facebook', 'az_alice' ); ?>" target="_blank"><i class="font-icon-social-facebook"></i><span>Share</span></a>
        <?php } ?>
        <?php if( !empty($options_alice['header_google_share']) && $options_alice['header_google_share'] == 1) { ?>
        <a class="az-share-google share-btn-footer" href="https://plus.google.com/share?url=<?php echo !empty( $url ) ? $url : ''; ?>" title="<?php _e( 'Share on Google Plus', 'az_alice' ); ?>" target="_blank"><i class="font-icon-social-google-plus"></i><span>Share</span></a>
        <?php } ?>
        <?php if( !empty($options_alice['header_pinterest_share']) && $options_alice['header_pinterest_share'] == 1) { ?>
        <a class="az-share-pinterest share-btn-footer" href="http://pinterest.com/pin/create/bookmarklet/?media=<?php echo !empty( $media[0] ) ? $media[0] : ''; ?>&amp;url=<?php echo !empty( $url ) ? $url : ''; ?>&amp;is_video=false&amp;description=<?php echo !empty( $title ) ? $title : ''; ?>" title="<?php _e( 'Share on Pinterest', 'az_alice' ); ?>" target="_blank"><i class="font-icon-social-pinterest"></i><span>Share</span></a>
        <?php } ?>
        <?php if( !empty($options_alice['header_linkedin_share']) && $options_alice['header_linkedin_share'] == 1) { ?>
        <a class="az-share-linkedin share-btn-footer" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo !empty( $url ) ? $url : ''; ?>&amp;title=<?php echo !empty( $title ) ? $title : ''; ?>" title="<?php _e( 'Share on Linkedin', 'az_alice' ); ?>" target="_blank"><i class="font-icon-social-linkedin"></i><span>Share</span></a>
        <?php } ?>
    </div>
</div>
<!-- End Modal Share -->
<?php } ?>

<?php if( !empty($options_alice['global_menu_search_button']) && $options_alice['global_menu_search_button'] == 'enable') { ?>
<!-- Start Modal Search -->
<div class="modal-container-search">
    <a class="close-modal-search">
        <div class="bars">
            <i class="bar top"></i>
            <i class="bar bottom"></i>
        </div>
    </a>
    <div class="modal-container-search-fake"></div>
    <div class="modal-search">
        <div class="modal-search-wrap">
            <form method="get" id="searchform" action="<?php echo home_url(); ?>/">
                <input id="search-modal" type="text" name="s" value="" autocomplete="off" placeholder="<?php _e('Search', 'az_alice'); ?>" />
            </form>
            <h4 class="search-subtitle"><?php _e('Type here &amp; click enter', 'az_alice'); ?></h4>
        </div>
    </div>
</div>
<!-- End Modal Search -->
<?php } ?>

<?php } ?>

<div id="main" class="">