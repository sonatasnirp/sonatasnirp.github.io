<?php get_header(); ?>

<?php
$options_alice = get_option('alice'); 

$dots_menu_navigation = get_post_meta($post->ID, 'az_dots_menu_display', true);

$team_main_url = '';
if( !empty( $options_alice['back_to_team_url_general'] ) ) { 
	$team_main_url = get_permalink( $options_alice['back_to_team_url_general'] );
} else {
	$team_main_url = '#';
}

$team_custom_url = get_post_meta($post->ID, 'az_back_team_url_custom', true);
if( !empty($team_custom_url) ) { 
	$team_custom_url = get_permalink( get_post_meta($post->ID, 'az_back_team_url_custom', true) );
} else {
	$team_custom_url = '#';
}
?>

<div id="content">
	<?php az_page_header($post->ID); ?>
	<section class="wrap_content">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div id="team" class="main-content single-post-team">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php 
				the_content();
				wp_link_pages(
					array(
						'before' => '<p><strong>'.__('Pages:', 'az_alice').'</strong> ',
						'after' => '</p>',
						'next_or_number' => 'number'
					)
				);
			?>
			</div>
		</div>

	<?php 
		// Category/Disciplines Navigation
		if( $options_alice['navigation_team_mode'] == "disciplines" ) { ?>
		<!-- Navigation Post Area -->
		<div class="normal-pagination">
		<?php 
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( ! empty( $prev_post ) ) : ?>
			<div class="prev-post"><?php be_previous_post_link('%link','<div class="pagination-inner">%title</div><span class="icon-arrow-pag font-icon-arrow-left-simple-thin-round"></span>',TRUE, null, 'disciplines'); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $next_post ) ) : ?>
			<div class="next-post"><?php be_next_post_link('%link','<div class="pagination-inner">%title</div><span class="icon-arrow-pag font-icon-arrow-right-simple-thin-round"></span>',TRUE, null,'disciplines'); ?></div>
			<?php endif; ?>

			<?php if( $options_alice['back_to_team'] == 1 && $options_alice['back_to_team_mode'] == "general" ) { ?>
			<div class="back-post"><a href="<?php echo esc_url( $team_main_url ); ?>" title="<?php echo __('Back to Team', 'az_alice'); ?>"><i class="icon-back-pag font-icon-grid"></i></a></div>
			<?php } ?>

			<?php if( $options_alice['back_to_team'] == 1 && $options_alice['back_to_team_mode'] == "custom" ) { ?>
			<div class="back-post"><a href="<?php echo esc_url( $team_custom_url ); ?>" title="<?php echo __('Back to Team', 'az_alice'); ?>"><i class="icon-back-pag font-icon-grid"></i></a></div>
			<?php } ?>
		</div>
		<!-- End Navigation Post Area -->
	<?php } ?>

	<?php 
		// Normal Pagination
		if( $options_alice['navigation_team_mode'] == "normal" ) { ?>
		<?php
		$count_posts = wp_count_posts('team');
		$published_posts = $count_posts->publish;
		if( $published_posts > 1 ) { ?>

		<!-- Navigation Post Area -->
		<div class="normal-pagination">
		<?php 
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( ! empty( $prev_post ) ) : ?>
			<div class="prev-post"><?php be_previous_post_link('%link','<div class="pagination-inner">%title</div><span class="icon-arrow-pag font-icon-arrow-left-simple-thin-round"></span>',false, null, 'disciplines'); ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $next_post ) ) : ?>
			<div class="next-post"><?php be_next_post_link('%link','<div class="pagination-inner">%title</div><span class="icon-arrow-pag font-icon-arrow-right-simple-thin-round"></span>',false, null,'disciplines'); ?></div>
			<?php endif; ?>

			<?php if( $options_alice['back_to_team'] == 1 && $options_alice['back_to_team_mode'] == "general" ) { ?>
			<div class="back-post"><a href="<?php echo esc_url( $team_main_url ); ?>" title="<?php echo __('Back to Team', 'az_alice'); ?>"><i class="icon-back-pag font-icon-grid"></i></a></div>
			<?php } ?>

			<?php if( $options_alice['back_to_team'] == 1 && $options_alice['back_to_team_mode'] == "custom" ) { ?>
			<div class="back-post"><a href="<?php echo esc_url( $team_custom_url ); ?>" title="<?php echo __('Back to Team', 'az_alice'); ?>"><i class="icon-back-pag font-icon-grid"></i></a></div>
			<?php } ?>
		</div>
		<!-- End Navigation Post Area -->
		<?php } ?>
	<?php } ?>

	<?php endwhile; endif; ?>
	</section>

	<?php if ( $dots_menu_navigation == 'show' ) { ?>
	<div class="dots-menu-navigation">
		<nav id="vertical-dots-menu">
			<ul>
			<?php az_dots_menu_navigation($post->ID); ?>
			</ul>
		</nav>
	</div>
	<?php } ?>

</div>

<?php get_footer(); ?>