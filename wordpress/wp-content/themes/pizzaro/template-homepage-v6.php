<?php
/**
 * The template for displaying the homepage v6.
 *
 * This page template will display any functions hooked into the `pizzaro_homepage_v6` action.
 *
 * Template name: Homepage v6
 *
 * @package pizzaro
 */

remove_action( 'pizzaro_content_top', 'pizzaro_breadcrumb', 10 );

remove_all_actions( 'pizzaro_header_v1' );

add_action( 'pizzaro_header_v1', 'pizzaro_skip_links',                         0 );
add_action( 'pizzaro_header_v1', 'pizzaro_site_branding',                      20 );
add_action( 'pizzaro_header_v1', 'pizzaro_secondary_navigation',               40 );
add_action( 'pizzaro_header_v1', 'pizzaro_primary_navigation',                 50 );
add_action( 'pizzaro_header_v1', 'pizzaro_social_icons',                       60 );
add_action( 'pizzaro_header_v1', 'pizzaro_credit',                             70 );

if ( is_woocommerce_activated() ) {
	add_action( 'pizzaro_header_v1', 'pizzaro_header_cart',                        30 );
}

do_action( 'pizzaro_before_homepage_v6' );

get_header( 'v1' ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			/**
			 * Functions hooked in to homepage action
			 *
			 * @hooked pizzaro_homepage_content      - 10
			 * @hooked pizzaro_product_categories    - 20
			 * @hooked pizzaro_recent_products       - 30
			 * @hooked pizzaro_featured_products     - 40
			 * @hooked pizzaro_popular_products      - 50
			 * @hooked pizzaro_on_sale_products      - 60
			 * @hooked pizzaro_best_selling_products - 70
			 */
			do_action( 'pizzaro_homepage_v6' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer( 'v5' );
