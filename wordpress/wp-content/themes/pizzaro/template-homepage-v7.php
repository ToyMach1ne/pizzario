<?php
/**
 * The template for displaying the homepage v7.
 *
 * This page template will display any functions hooked into the `pizzaro_homepage_v7` action.
 *
 * Template name: Homepage v7
 *
 * @package pizzaro
 */

remove_action( 'pizzaro_content_top', 'pizzaro_breadcrumb', 10 );

do_action( 'pizzaro_before_homepage_v7' );

get_header( 'v4' ); ?>

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
			do_action( 'pizzaro_homepage_v7' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer( 'v4' );
