<?php
$options_alice = get_option('alice'); 

// Blog Layout & Columns
$blog_layout = $options_alice['blog_layout_mode'];
$search_layout = $options_alice['search_layout_mode'];
$archive_layout = $options_alice['archive_layout_mode'];

$blog_layout_mode = (!empty($options_alice['blog_layout_mode'])) ? $options_alice['blog_layout_mode'] : 'wide';
$blog_grid_cols = (!empty($options_alice['blog_grid_columns'])) ? $options_alice['blog_grid_columns'] : '3';

// Post Thumbnails Size
$wide_img_size = (!empty($options_alice['wide_post_thumb_size'])) ? $options_alice['wide_post_thumb_size'] : '1000x500';
$grid_one_img_size = (!empty($options_alice['grid_one_post_thumb_size'])) ? $options_alice['grid_one_post_thumb_size'] : '1000x600';
$grid_two_img_size = (!empty($options_alice['grid_two_post_thumb_size'])) ? $options_alice['grid_two_post_thumb_size'] : '550x550';
$grid_three_img_size = (!empty($options_alice['grid_three_post_thumb_size'])) ? $options_alice['grid_three_post_thumb_size'] : '400x400';
$grid_four_img_size = (!empty($options_alice['grid_four_post_thumb_size'])) ? $options_alice['grid_four_post_thumb_size'] : '350x350';

if ( has_post_thumbnail() ) {
	$thumb = get_post_thumbnail_id( $post->ID );
	$img_url = wp_get_attachment_url( $thumb, 'full' );

    // Explode Featured Image Sizes
    $wide_th_output = array_map('trim', preg_split("/[x|X|*]/", esc_attr($wide_img_size) ));
    $grid_one_th_output = array_map('trim', preg_split("/[x|X|*]/", esc_attr($grid_one_img_size) ));
    $grid_two_th_output = array_map('trim', preg_split("/[x|X|*]/", esc_attr($grid_two_img_size) ));
    $grid_three_th_output = array_map('trim', preg_split("/[x|X|*]/", esc_attr($grid_three_img_size) ));
    $grid_four_th_output = array_map('trim', preg_split("/[x|X|*]/", esc_attr($grid_four_img_size) ));

	switch ( $blog_layout_mode ) {

    	case 'grid':
    		switch ( $blog_grid_cols ) {
    			case '1':
    				$img = aq_resize( $img_url, $grid_one_th_output[0], $grid_one_th_output[1], true, false, true );
    				break;

    			case '2':
    				$img = aq_resize( $img_url, $grid_two_th_output[0], $grid_two_th_output[1], true, false, true );
    				break;

    			case '3':
                    $img = aq_resize( $img_url, $grid_three_th_output[0], $grid_three_th_output[1], true, false, true );
                    break;

                case '4':
    				$img = aq_resize( $img_url, $grid_four_th_output[0], $grid_four_th_output[1], true, false, true );
    				break;

    			default:
    				$img = aq_resize( $img_url, '400', '400', true, false, true );
    		}
    		break;

        case 'wide':
                $img = aq_resize( $img_url, $wide_th_output[0], $wide_th_output[1], true, false, true );
            break;
    	
    	default:
    		$img = aq_resize( $img_url, '1000', '500', true, false, true );
    }

    $mode_img = ' style="background-image: url('.$img[0].');"';
} else {
	$mode_img = '';
}

?>

<div class="post-creative post-image"<?php echo !empty( $mode_img ) ? $mode_img : ''; ?>>
	<a class="post-link" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
        <?php if ( is_front_page() || is_home() ) { ?>
            <?php if($blog_layout == 'grid') { 
                global $currentID;
                $currentID = get_the_ID();
                $currentNumber = az_get_post_number($currentID); ?>
            <span class="count-number-post"><?php echo esc_attr( $currentNumber ); ?></span>
            <?php } ?>
        <?php } ?>
		<div class="post-caption">
			<div class="post-naming">
				<h2 class="post-title"><?php the_title(); ?></h2>
				<span class="post-date"><time datetime="<?php the_time( 'c' ); ?>"><?php the_time( get_option('date_format') ); ?></time></span>
			</div>
		</div>
	</a>
</div>
