<?php
remove_filter( 'woocommerce_get_price', array( YITH_WC_Dynamic_Pricing_Frontend(), 'get_price' ) );
$tax_display_mode      = get_option( 'woocommerce_tax_display_shop' );
if ( $label_table != '' ):
    ?>

    <p class="ywdpd-table-discounts-label"><strong><?php echo $label_table ?></strong>
        <?php if ( $until != '' ) {
            echo "<span>$until</span>";
        } ?>
    </p>
<?php endif; ?>
    <table id="ywdpd-table-discounts">
        <tr>
            <th><?php echo $label_quantity ?></th>
            <?php foreach ( $rules as $rule ): ?>
                <td data-qtymin="<?php echo esc_attr( $rule[ 'min_quantity' ]  )?>" data-qtymax="<?php echo esc_attr( $rule[ 'max_quantity' ]  )?>"><?php echo $rule[ 'min_quantity' ] ?>
                    <?php
                        if( $rule[ 'max_quantity' ] != $rule[ 'min_quantity' ] ) {
                            echo ( $rule['max_quantity'] != '*' ) ? '-' . $rule['max_quantity'] : '+';
                        }
                    ?>
                </td>
            <?php endforeach ?>
        </tr>
        <tr>
            <th><?php echo $label_price ?></th>
            <?php foreach ( $rules as $rule ):

                if ( $product->product_type == 'variable' ) {
                    $prices = $product->get_variation_prices();

                    $prices = isset( $prices[ 'price' ] ) ? $prices[ 'price' ] : array ();
                    if ( $prices ) {

                        $min_price          = current ( $prices );
                        $min_key = array_search( $min_price, $prices );

                        if( YITH_WC_Dynamic_Pricing_Helper()->valid_product_to_apply_bulk( $main_rule, wc_get_product( $min_key  ) ) ){
                            $discount_min_price = ywdpd_get_discounted_price_table ( $min_price, $rule );
                        }else{
                            $discount_min_price = $min_price;
                        }

                        $max_price = end( $prices );
                        $max_key   = array_search( $max_price, $prices );
                        if( YITH_WC_Dynamic_Pricing_Helper()->valid_product_to_apply_bulk( $main_rule, wc_get_product( $max_key  )) ){
                            $discount_max_price = ywdpd_get_discounted_price_table ( $max_price, $rule );
                        }else{
                            $discount_max_price = $max_price;
                        }
                        //@since 1.1.0
                        if( $discount_min_price !== $discount_max_price ){
                            $html = $discount_min_price < $discount_max_price ? sprintf ( _x ( '%1$s&ndash;%2$s', 'Price range: from-to', 'woocommerce' ), wc_price ( $product->get_display_price($discount_min_price) ), wc_price ( $product->get_display_price($discount_max_price) ) ) : sprintf ( _x ( '%1$s&ndash;%2$s', 'Price range: from-to', 'woocommerce' ), wc_price ( $product->get_display_price( $discount_max_price ) ), wc_price (  $product->get_display_price( $discount_min_price )  ) );
                        }else{

                            $html = wc_price ( $product->get_display_price($discount_min_price)  );
                        }

                    }

                } else {

	                $price = $product->get_price();
	                //check if the product or the variation has discount

	                if ( YITH_WC_Dynamic_Pricing_Helper()->valid_product_to_apply_bulk( $main_rule, $product ) ) {
		                $discount_price = ywdpd_get_discounted_price_table( $price, $rule );
	                } else {
		                $discount_price = $price;
	                }

	                $discount_price = ( $tax_display_mode == 'excl' ) ? $product->get_price_excluding_tax( 1, $discount_price ) : $product->get_price_including_tax( 1, $discount_price );

	                $html           = wc_price( $discount_price );
                }
                ?>
                <td><?php echo apply_filters ( 'ywdpd_show_price_on_table_pricing', $html, $rule, $product ); ?></td>
            <?php endforeach ?>
        </tr>
    </table>

<?php if ( $note != '' ) {
    echo "<p class=\"ywdpd-table-discounts-note\">{$note}</p>";
} ?>

<?php
add_filter( 'woocommerce_get_price', array( YITH_WC_Dynamic_Pricing_Frontend(), 'get_price' ) );