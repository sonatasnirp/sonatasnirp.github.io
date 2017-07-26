<?php get_header(); ?>

<?php
$options_alice = get_option('alice'); 

$dots_menu_navigation = get_post_meta($post->ID, 'az_dots_menu_display', true);

$blog_main_url = '';
if( !empty( $options_alice['back_to_posts_url'] ) ) { 
	$blog_main_url = get_permalink( $options_alice['back_to_posts_url'] );
} else {
	$blog_main_url = '#';
}
?>

<div id="content">
	<?php az_page_header($post->ID); ?>
	<section class="wrap_content">
		<div id="blog" class="main-content single-post">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
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

			<div class="entry-meta-area">
				<div class="left-div">
					<div class="date item-meta-area item-meta-equals">
						<span class="post-date"><time datetime="<?php the_time( 'c' ); ?>"><?php the_time( get_option('date_format') ); ?></time></span>
					</div>
					<div class="author item-meta-area item-meta-equals">
						<span class="post-author-profile"><?php _e( 'Written by ', 'az_alice' ); ?><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php _e( 'Author Profile', 'az_alice' ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a></span>
					</div>
				</div>
				<div class="right-div">
					<div class="categories item-meta-area item-meta-equals">
						<span class="entry-categories"><?php _e('Posted in: ', 'az_alice') ?> <?php the_category(', ') ?></span>
					</div>
					<div class="tags item-meta-area item-meta-equals">
						<span class="entry-tags"><?php the_tags( __('Tagged:', 'az_alice') . ' ', ', ', ''); ?></span>
					</div>
				</div>
			</div>

			<?php if( comments_open() ) { ?>
			<!-- Comments Template Area -->
			<div class="comment-area">
				<?php comments_template('', true); ?>
			</div>
			<!-- End Comments Template Area -->
			<?php } ?>
		</div>

		<?php
		$count_posts = wp_count_posts();
		$published_posts = $count_posts->publish;
		if( $published_posts > 1 ) { ?>

		<!-- Navigation Post Area -->
		<div class="normal-pagination">
		<?php 
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( ! empty( $next_post ) ) : ?>
			<div class="prev-post"><a href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>"><div class="pagination-inner"><?php echo esc_attr( $next_post->post_title ); ?></div><span class="icon-arrow-pag font-icon-arrow-left-simple-thin-round"></span></a></div>
			<?php endif; ?>
			
			<?php if ( ! empty( $prev_post ) ) : ?>
			<div class="next-post"><a href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>"><div class="pagination-inner"><?php echo esc_attr( $prev_post->post_title ); ?></div><span class="icon-arrow-pag font-icon-arrow-right-simple-thin-round"></span></a></div>
			<?php endif; ?>

			<?php if( $options_alice['back_to_posts'] == 1 ) { ?>
			<div class="back-post"><a href="<?php echo esc_url( $blog_main_url ); ?>" title="<?php echo __('Back to Posts', 'az_alice'); ?>"><i class="icon-back-pag font-icon-grid"></i></a></div>
			<?php } ?>
		</div>
		<!-- End Navigation Post Area -->

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