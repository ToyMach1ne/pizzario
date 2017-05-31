<?php
/**
 * The template for displaying product widget entries
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product; ?>

<li>
	<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
		<?php echo $product->get_image(); ?>
		<span class="product-title"><?php echo wp_kses_post( $product->get_title() ); ?></span>
	</a>
	<?php if ( ! empty( $show_rating ) ) : ?>
		<?php
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.7', '<' ) ) {
			echo wp_kses_post( $product->get_rating_html() );
		} else {
			echo wc_get_rating_html( $product->get_average_rating() );
		}
		?>
	<?php endif; ?>
	<span class="widget-price"><?php echo $product->get_price_html(); ?></span>
</li>
