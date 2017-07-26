<?php get_header(); ?>

<?php  
$options_alice = get_option('alice'); 

// Set class based on blog layout
$search_layout = $options_alice['search_layout_mode'];
$container_class = 'normal-blog-pagination';

// Grid Wrap Posts
if($search_layout == 'grid') {
	$limit = $options_alice['search_grid_columns'];
	$data_cols = ' data-cols="'.$limit.'"';
} else {
	$data_cols = '';
}

?>

<div id="content">

<?php get_template_part('framework/core/search/az-search'); ?>

	<section class="wrap_content">
		
		<div id="blog" class="<?php echo esc_attr( $search_layout ); ?>"<?php echo !empty( $data_cols ) ? $data_cols : ''; ?>>
			<div id="blog-posts-container" class="<?php echo esc_attr( $container_class ); ?>">

			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

			<article <?php post_class('item-blog'); ?> id="post-<?php the_ID(); ?>">
				<div class="post-container">
					<?php get_template_part( 'content' ); ?>
				</div>
			</article>

			<?php endwhile; ?>

			<?php else: ?>
			<!-- No results -->
			<div class="no-results"> 
				<h3 class="post-title"><?php _e('Your search did not match any entries!', 'az_alice') ?></h2>
				<p><?php _e('Sorry, but you are looking for something that isn\'t here.', 'az_alice') ?></p>
				<a class="az-button open-modal-search"><?php _e('Try Again?', 'az_alice') ?></a>
			</div>
			<!-- End No Results -->
			<?php endif; ?>

			</div>
		</div>

		<?php echo az_normal_pagination(); ?>

	</section>

</div>
<?php get_footer(); ?>