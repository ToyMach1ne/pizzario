<?php
/**
 * Filter functions for Styling Section of Theme Options
 */

if ( ! function_exists( 'redux_toggle_use_predefined_colors' ) ) {
	function redux_toggle_use_predefined_colors( $enable ) {
		global $pizzaro_options;

		if ( isset( $pizzaro_options['use_predefined_color'] ) && $pizzaro_options['use_predefined_color'] ) {
			$enable = true;
		} else {
			$enable = false;
		}

		return $enable;
	}
}

if ( ! function_exists( 'sass_darken' ) ) {
	function sass_darken( $hex, $percent ) {
		preg_match( '/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $primary_colors );
		str_replace( '%', '', $percent );
		$color = "#";
		for( $i = 1; $i <= 3; $i++ ) {
			$primary_colors[$i] = hexdec( $primary_colors[$i] );
			if ( $percent > 50 ) $percent = 50;
			$dv = 100 - ( $percent * 2 );
			$primary_colors[$i] = round( $primary_colors[$i] * ( $dv ) / 100 );
			$color .= str_pad( dechex( $primary_colors[$i] ), 2, '0', STR_PAD_LEFT );
		}
		return $color;
	}
}

if ( ! function_exists( 'sass_lighten' ) ) {
	function sass_lighten( $hex, $percent ) {
		preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $hex, $primary_colors);
		str_replace('%', '', $percent);
		$color = "#";
		for($i = 1; $i <= 3; $i++) {
			$primary_colors[$i] = hexdec($primary_colors[$i]);
			$primary_colors[$i] = round($primary_colors[$i] * (100+($percent*2))/100);
			$color .= str_pad(dechex($primary_colors[$i]), 2, '0', STR_PAD_LEFT);
		}
		return $color;
	}
}

if ( ! function_exists( 'redux_apply_custom_color_css' ) ) {
	function redux_apply_custom_color_css() {
		global $pizzaro_options;

		if ( isset( $pizzaro_options['use_predefined_color'] ) && $pizzaro_options['use_predefined_color'] ) {
			return;
		}

		$how_to_include = isset( $pizzaro_options['include_custom_color'] ) ? $pizzaro_options['include_custom_color'] : '1';

		if ( $how_to_include != '1' ) {
			return;
		}

		?><style type="text/css"><?php echo redux_get_custom_color_css(); ?></style><?php
	}
}

if ( ! function_exists( 'redux_get_custom_color_css' ) ) {
	function redux_get_custom_color_css() {
		global $pizzaro_options;

		$primary_color      = isset( $pizzaro_options['custom_primary_color'] ) ? $pizzaro_options['custom_primary_color'] : '#c00a27';

		$active_background  = sass_darken( $primary_color, '100%' );
		$active_border      = sass_darken( $primary_color, '100%' );

		$styles 	        = '
		button,
		.button,
		#scrollUp,
		.header-v1,
		.header-v2,
		.header-v3,
		.header-v4,
		.header-v5,
		.added_to_cart,
		.header-v1 .stuck,
		.header-v2 .stuck,
		.header-v3 .stuck,
		.header-v4 .stuck,
		.header-v5 .stuck,
		input[type="reset"],
		input[type="submit"],
		input[type="button"],
		.dark .create-your-own a,
		.owl-dots .owl-dot.active,
		.pizzaro-handheld-footer-bar,
		.widget_nav_menu .menu li:hover,
		.related > h2:first-child:after,
		.upsells > h2:first-child:after,
		.widget_nav_menu .menu li::after,
		.section-products .section-title:after,
		.pizzaro-handheld-footer-bar ul li > a,
		.banners .banner .caption .banner-price,
		.section-tabs .nav .nav-item.active a::after,
		.products-with-gallery-tabs.section-tabs .nav,
		.section-recent-post .post-info .btn-more:hover,
		.section-sale-product .price-action .button:hover,
		.list-no-image-view ul.products li.product::before,
		.woocommerce-account .customer-login-form h2::after,
		.section-coupon .caption .coupon-info .button:hover,
		.page-template-template-homepage-v2 .header-v2 .stuck,
		.woocommerce-cart .pizzaro-order-steps ul .cart .step,
		.list-no-image-cat-view ul.products li.product::before,
		.pizzaro-handheld-footer-bar ul li.search .site-search,
		.widget.widget_price_filter .ui-slider .ui-slider-handle,
		.list-no-image-view .products .owl-item>.product::before,
		.list-no-image-view ul.products li.product .button:hover,
		.woocommerce-checkout .pizzaro-order-steps ul .cart .step,
		.woocommerce-cart .cart-collaterals+.cross-sells h2::after,
		.footer-v1.site-footer .site-address .address li+li::before,
		.list-no-image-cat-view ul.products li.product .button:hover,
		.header-v4.lite-bg .primary-navigation .menu > li > a::before,
		.woocommerce-checkout .pizzaro-order-steps ul .checkout .step,
		.kc-section-tab.kc_tabs .kc_tabs_nav>.ui-tabs-active>a::after,
		.list-no-image-view .products .owl-item>.product .button:hover,
		.page-template-template-homepage-v6 .footer-social-icons ul li a:hover,
		.list-view.left-sidebar.columns-1 ul.products li.product .button:hover,
		.products-card .media .media-left ul.products li.product .button:hover,
		.products-card .media .media-right ul.products li.product .button:hover,
		.page-template-template-homepage-v6 .primary-navigation > ul > li:hover,
		.list-view.right-sidebar.columns-1 ul.products li.product .button:hover,
		.page-template-template-homepage-v6 .secondary-navigation .menu li:hover,
		.page-template-template-homepage-v6 .secondary-navigation .menu li::after,
		.page-template-template-homepage-v6 .main-navigation ul.menu ul li:hover > a,
		.list-view.left-sidebar.columns-1 .products .owl-item>.product .button:hover,
		.list-view.right-sidebar.columns-1 .products .owl-item>.product .button:hover,
		.woocommerce-order-received.woocommerce-checkout .pizzaro-order-steps ul .step,
		.page-template-template-homepage-v6 .main-navigation ul.nav-menu ul li:hover > a,
		.page-template-template-homepage-v2 .products-with-gallery-tabs.section-tabs .nav,
		.stretch-full-width .store-locator .store-search-form form .button,
		.banner.social-block .caption .button:hover,
		.wpsl-search #wpsl-search-btn,
		.lite-bg.header-v4 .primary-navigation .menu .current-menu-item>a::before {
			background-color: ' . $primary_color . ';
		}

		.custom .tp-bullet.selected,
		.home-v1-slider .btn-primary,
		.home-v2-slider .btn-primary,
		.home-v3-slider .btn-primary,
		.products-with-gallery-tabs.kc_tabs>.kc_wrapper>.kc_tabs_nav,
		.products-with-gallery-tabs.kc_tabs .kc_tabs_nav li.ui-tabs-active a::after {
			background-color: ' . $primary_color . ' !important;
		}

		.lite-bg.header-v4 #pizzaro-logo,
		.lite-bg.header-v3 #pizzaro-logo,
		.site-footer.footer-v5 #pizzaro-logo,
		.site-footer.footer-v4 .footer-logo #pizzaro-logo,
		.page-template-template-homepage-v4 .header-v3 #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v1 .site-branding #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v2 .site-branding #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v3 .site-branding #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v4 .site-branding #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v5 .site-branding #pizzaro-logo,
		.page-template-template-homepage-v6 .header-v6 .site-branding #pizzaro-logo {
			fill: ' . $primary_color . ';
		}

		.section-events .section-title,
		.section-product-categories .section-title,
		.section-products-carousel-with-image .section-title,
		.section-product .product-wrapper .product-inner header .sub-title,
		.section-recent-post .post-info .btn-more,
		.section-coupon .caption .coupon-code,
		.widget_layered_nav li:before,
		.product_list_widget .product-title,
		.product_list_widget li>a,
		#payment .payment_methods li label a:hover,
		article.post.format-link .entry-content p a,
		.page-template-template-contactpage .store-info a {
			color: ' . $primary_color . ';
		}

		.section-recent-posts .section-title,
		.terms-conditions .entry-content .section.contact-us p a {
			color: ' . sass_darken( $primary_color, '14%' ) . ';
		}

		.secondary-navigation ul.menu>li>a, .secondary-navigation ul.nav-menu>li>a {
			color: ' . sass_lighten( $primary_color, '48%' ) . ';
		}

		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.button,
		.added_to_cart,
		.section-sale-product .price-action .button:hover,
		.section-recent-post .post-info .btn-more,
		.section-recent-post .post-info .btn-more:hover,
		.section-coupon .caption .coupon-info .button:hover,
		.widget.widget_price_filter .ui-slider .ui-slider-handle:last-child,
		#order_review_heading::after,
		#customer_details .woocommerce-billing-fields h3::after,
		#customer_details .woocommerce-shipping-fields h3::after,
		.woocommerce-cart .pizzaro-order-steps ul .cart .step,
		.woocommerce-checkout .pizzaro-order-steps ul .checkout .step,
		.page-template-template-homepage-v6 .footer-social-icons ul li a:hover,
		.woocommerce-order-received.woocommerce-checkout .pizzaro-order-steps ul .complete .step,
		.cart-collaterals h2::after,
		.widget_nav_menu .menu li:hover a,
		.page-template-template-contactpage .contact-form h2:after,
		.page-template-template-contactpage .store-info h2:after,
		.banner.social-block .caption .button:hover,
		#byconsolewooodt_checkout_field h2::after {
			border-color: ' . $primary_color . ';
		}

		.pizzaro-order-steps ul .step {
			border-color: ' . sass_lighten( $primary_color, '14%' ) . ';
		}

		button,
		.button:hover,
		.added_to_cart:hover,
		#respond input[type=submit],
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		.dark .create-your-own a:hover,
		.wc-proceed-to-checkout .button,
		.main-navigation ul.menu ul a:hover,
		.main-navigation ul.menu ul li:hover>a,
		.main-navigation ul.nav-menu ul a:hover,
		.main-navigation ul.nav-menu ul li:hover>a,
		.main-navigation div.menu ul.nav-menu ul a:hover,
		.main-navigation div.menu ul.nav-menu ul li:hover>a,
		.stretch-full-width .store-locator .store-search-form form .button:hover {
		    background-color: ' . sass_darken( $primary_color, '8%' ) . ';
		}

		#respond input[type=submit]:hover {
		    background-color: ' . sass_darken( $primary_color, '12%' ) . ';
		}

		.single-product div.product .woocommerce-product-gallery .flex-control-thumbs li img.flex-active,
		.single-product.style-2 div.product .summary .pizzaro-wc-product-gallery .pizzaro-wc-product-gallery__wrapper .pizzaro-wc-product-gallery__image.flex-active-slide {
		    border-bottom-color: ' . $primary_color . ';
		}

		@media (max-width: 1025px) {
			.page-template-template-homepage-v2 .header-v2 {
				background-color: ' . $primary_color . ';
			}
		}';

		return $styles;
	}
}

if ( ! function_exists( 'redux_load_external_custom_css' ) ) {
	function redux_load_external_custom_css() {
		global $pizzaro_options;

		if ( isset( $pizzaro_options['use_predefined_color'] ) && $pizzaro_options['use_predefined_color'] ) {
			return;
		}

		$how_to_include = isset( $pizzaro_options['include_custom_color'] ) ? $pizzaro_options['include_custom_color'] : '1';

		if ( $how_to_include == '1' ) {
			return;
		}

		$custom_color_file = get_stylesheet_directory() . '/custom-color.css';

		if ( file_exists( $custom_color_file ) ) {
			wp_enqueue_style( 'pizzaro-custom-color', get_stylesheet_directory_uri() . '/custom-color.css' );
		}
	}
}

if ( ! function_exists( 'redux_toggle_custom_css_page' ) ) {
	function redux_toggle_custom_css_page() {
		global $pizzaro_options;

		if ( isset( $pizzaro_options['use_predefined_color'] ) && $pizzaro_options['use_predefined_color'] ) {
			$should_add = false;
		} else {
			if ( !isset( $pizzaro_options['include_custom_color'] ) ) {
				$pizzaro_options['include_custom_color'] = '1';
			}

			if ( $pizzaro_options['include_custom_color'] == '2' ) {
				$should_add = true;
			} else {
				$should_add = false;
			}
		}

		return $should_add;
	}
}
