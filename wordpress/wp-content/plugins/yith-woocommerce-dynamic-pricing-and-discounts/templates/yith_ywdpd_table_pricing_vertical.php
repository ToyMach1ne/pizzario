<?php
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
            <th><?php echo $label_price ?></th>
            
        </tr>
        <?php foreach ( $rules as $rule ): ?>
            <tr>
                <td><?php echo $rule[ 'min_quantity' ] ?>
                    <?php
                    if( $rule[ 'max_quantity' ] != $rule[ 'min_quantity' ] ) {
                        echo ( $rule['max_quantity'] != '*' ) ? '-' . $rule['max_quantity'] : '+';
                    }
                    ?>
                </td>
                
            <?php 

                if ( $product->product_type == 'variable' ) {
                    $prices = $product->get_variation_prices ();
                    $prices = isset( $prices[ 'price' ] ) ? $prices[ 'price' ] : array ();
                    if ( $prices ) {
                        $min_price          = current ( $prices );
                        $discount_min_price = ywdpd_get_discounted_price_table ( $min_price, $rule );
                        $max_price          = end ( $prices );
                        $discount_max_price = ywdpd_get_discounted_price_table ( $max_price, $rule );

                        $html = $discount_min_price !== $discount_max_price ? sprintf ( _x ( '%1$s&ndash;%2$s', 'Price range: from-to', 'woocommerce' ), wc_price ( $discount_min_price ), wc_price ( $discount_max_price ) ) : wc_price ( $discount_min_price );
                    }

                } else {
                    $price          = ( WC ()->cart->tax_display_cart == 'excl' ) ? $product->get_price_excluding_tax () : $product->get_price_including_tax ();
                    $discount_price = ywdpd_get_discounted_price_table ( $price, $rule );

                    $html = wc_price ( $discount_price );
                }
                ?>
                <td><?php echo apply_filters ( 'ywdpd_show_price_on_table_pricing', $html, $rule, $product ); ?></td>
            </tr>
    <?php endforeach ?>
        
    </table>
<?php if ( $note != '' ) {
    echo "<p class=\"ywdpd-table-discounts-note\">{$note}</p>";
} ?>