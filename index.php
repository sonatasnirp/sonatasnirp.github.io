<?php get_header(); ?>

<?php  
$options_alice = get_option('alice'); 

// Set class based on blog layout
$blog_layout = $options_alice['blog_layout_mode'];

// Set class based on pagination
$blog_pagination = $options_alice['blog_pagination_select'];

if($blog_pagination == 'infinite_scroll_blog_pagination') {
	$container_class = 'infinite-scroll-pagination';
} else {
	$container_class = 'normal-blog-pagination';
}

// Grid Wrap Posts
if($blog_layout == 'grid') {
	$limit = $options_alice['blog_grid_columns'];
	$data_cols = ' data-cols="'.$limit.'"';
} else {
	$data_cols = '';
}

?>

<div id="content">
	
	<?php if ( is_front_page() ) { ?>
		<div class="no-page-header colorful-version"></div>
	<?php } else { ?>
		<?php az_page_header(get_option('page_for_posts')); ?>
	<?php } ?>

	<section class="wrap_content">
		
		<div id="blog" class="<?php echo esc_attr( $blog_layout ); ?>"<?php echo !empty( $data_cols ) ? $data_cols : ''; ?>>
			<div id="blog-posts-container" class="<?php echo esc_attr( $container_class ); ?>">

			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>


			<article <?php post_class('item-blog'); ?> id="post-<?php the_ID(); ?>">
				<div class="post-container">
					<?php get_template_part( 'content' ); ?>
				</div>
			</article>

			<?php endwhile; endif; ?>

			</div>
		</div>

		<?php if($blog_pagination == 'infinite_scroll_blog_pagination') { ?>
		
		<div class="infinite-scroll">
			<span class="preloader"></span>
			<p class="end"><?php _e( 'No More Posts', 'az_alice' ); ?></p>
			<a id="infinite-link" href="<?php echo next_posts( 0, false ); ?>"><?php _e( 'Load More Posts', 'az_alice' ); ?></a>
		</div>

		<?php } else {

		echo az_normal_pagination();

		} ?>


	</section>

</div>
<?php get_footer(); ?>