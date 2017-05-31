<?php
/**
 * The Template for displaying WooCommerce Shop Pages
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/wc-product-shop.php.
 *
 * @var $this AMPHTML_Template
 */

$view = $this->options->get('shop_view');
?>
<header class="page-header">
	<h1 class="amphtml-title"><?php woocommerce_page_title() ?></h1>
	<?php do_action( 'woocommerce_archive_description' ); ?>
</header>
<div <?php if( $view === 'grid' ): ?>id="wc-archive-wrap"<?php endif; ?>>
	<?php
	if ( have_posts() ):
		while ( have_posts() ): the_post();
			$id = get_the_ID();
			$this->set_archive_page_post( $id, false );
			echo $this->render( 'wc-content-product' );
		endwhile;
	endif;
	?>
</div>
<?php echo $this->render( 'pagination' ); ?>