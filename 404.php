<?php 
$az_options_show_header = true; // Header Enabled
$az_options_show_footer = false; // Footer Disabled

$options_alice = get_option('alice');

$error_default_title = __('Nothing to see here! (404 Error)', 'az_alice');
$error_default_subheading = __('We are really sorry but the page you requested is missing!', 'az_alice');
$error_default_btn = __('Go Back', 'az_alice');

$error_title = esc_attr($options_alice['error_custom_title']);
$error_subheading = esc_attr($options_alice['error_custom_subheading']);
$error_back_btn = esc_attr($options_alice['error_custom_back_button']);

if( !empty($options_alice['error_customize_settings']) && $options_alice['error_customize_settings'] == 1) {

    $error_title_output = ( !empty( $error_title ) ? $error_title : $error_default_title );
    $error_subheading_output = ( !empty( $error_subheading ) ? $error_subheading : $error_default_subheading );
    $error_back_btn_output = ( !empty( $error_back_btn ) ? $error_back_btn : $error_default_btn );
    $error_bg_left_side = ( !empty( $options_alice['error_left_side_bg'] ) ) ? ' style="background-color: '.$options_alice['error_left_side_bg'].'; border-right-color: transparent;"' : '';
    $error_bg_right_side = ( !empty( $options_alice['error_left_side_bg'] ) ) ? ' style="background-color: '.$options_alice['error_right_side_bg'].';"' : '';

    $error_color_title = ( !empty( $options_alice['error_left_side_title_color'] ) ) ? ' style="color: '.$options_alice['error_left_side_title_color'].';"' : '';
    $error_color_subheading = ( !empty( $options_alice['error_left_side_subheading_color'] ) ) ? ' style="color: '.$options_alice['error_left_side_subheading_color'].';"' : '';
    $error_color_button = ( !empty( $options_alice['error_right_side_button'] ) ) ? ' style="color: '.$options_alice['error_right_side_button'].';"' : '';

} else {

    $error_bg_left_side = $error_bg_right_side = $error_color_title = $error_color_subheading = $error_color_button = '';
    $error_title_output = $error_default_title;
    $error_subheading_output = $error_default_subheading;
    $error_back_btn_output = $error_default_btn;

}

$allowed_tags = array(
    'br' => array(),
    'strong' => array()
);
?>

<?php get_header(); ?>

<div id="content">
    <section id="error-page">
        <div class="no-page-header colorful-version"></div>
        
        <div class="fake-layer-spacer full-height"></div>

        <div class="fake-block-title-header">
            <div class="title-header-container">
                <div class="box-content"<?php echo !empty( $error_bg_left_side ) ? $error_bg_left_side : ''; ?>>
                    <div class="box-content-titles">
                        <h1 class="error-title"<?php echo !empty( $error_color_title ) ? $error_color_title : ''; ?>><?php echo wp_kses($error_title_output, $allowed_tags); ?></h1>
                        <h2 class="error-subheading"<?php echo !empty( $error_color_subheading ) ? $error_color_subheading : ''; ?>><?php echo wp_kses($error_subheading_output, $allowed_tags); ?></h2>
                        <a href="javascript:javascript:history.go(-1)" class="back-home"<?php echo !empty( $error_color_button ) ? $error_color_button : ''; ?>><?php echo wp_kses($error_back_btn_output, $allowed_tags); ?></a>
                    </div>
                </div>
                <div class="box-content-link"<?php echo !empty( $error_bg_right_side ) ? $error_bg_right_side : ''; ?>>
                    <div class="box-content-titles-link">
                        <a href="javascript:javascript:history.go(-1)" class="back-home"<?php echo !empty( $error_color_button ) ? $error_color_button : ''; ?>><?php echo wp_kses($error_back_btn_output, $allowed_tags); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>