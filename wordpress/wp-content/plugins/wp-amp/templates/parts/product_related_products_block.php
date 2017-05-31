<?php
global $product;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}
$posts_per_page = 4;
if ( ! $related = $product->get_related( $posts_per_page ) ) {
	return;
}
?>
<div class="related-products-container">
	<h2><?php _e( 'Related Products' ); ?></h2>
	<div class="related-products-list">
		<?php foreach ( $related as $product_id ): ?>
			<?php
			$product = wc_get_product( $product_id );
			$img     = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ) );
			$link    = $this->get_amphtml_link( get_the_permalink( $product_id ), $product_id );
			?>
			<div class="related-product-block">
				<a class="related-product-link" href="<?php echo $link; ?>">
					<amp-img width="140" height="140"
					         src="<?php echo $img[0]; ?>"
					         alt="<?php esc_attr_e( $product->get_title() ); ?>"
					         width="<?php echo $img[1]; ?>"
					         height="<?php echo $img[2]; ?>">
					</amp-img>
					<p class="wc-related-name"><?php echo $product->get_title(); ?></p>
				</a>
				<?php
				$rating_count = $product->get_rating_count();
				if ( $this->get_option('product_related_rating') && $rating_count > 0 ) :
					$rating = round( $product->get_average_rating() );
					?>
					<p class="wc-related-start">
						<?php for ( $i = 1; $i <= 5; $i ++ ):
							if ( $rating >= $i ): ?>
								★
							<?php else: ?>
								☆
							<?php endif;
						endfor; ?>
					</p>
				<?php endif; ?>
				<?php if ( $this->get_option( 'product_related_price' ) ): ?>
					<p class="wc-related-price"><?php echo $product->get_price_html(); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>