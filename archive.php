<?php get_header(); ?>

<?php  
$options_alice = get_option('alice'); 

// Set class based on blog layout
$archive_layout = $options_alice['archive_layout_mode'];
$container_class = 'normal-blog-pagination';

// Grid Wrap Posts
if($archive_layout == 'grid') {
	$limit = $options_alice['archive_grid_columns'];
	$data_cols = ' data-cols="'.$limit.'"';
} else {
	$data_cols = '';
}

?>

<div id="content">

<?php get_template_part('framework/core/archives/az-archives'); ?>

	<section class="wrap_content">
		
		<div id="blog" class="<?php echo esc_attr( $archive_layout ); ?>"<?php echo !empty( $data_cols ) ? $data_cols : ''; ?>>
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

		<?php echo az_normal_pagination(); ?>

	</section>

</div>
<?php get_footer(); ?>