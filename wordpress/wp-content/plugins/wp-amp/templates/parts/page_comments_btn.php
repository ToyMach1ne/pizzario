<?php
if ( $this->options->get( 'page_comments_btn' ) && comments_open() ):
    $url = $this->get_canonical_url();
    if( !empty( $this->options->get( 'mobile_amp' ) ) ) {
        $url = add_query_arg( array(
            'view-original-redirect' => '1',
        ), $url );
    }
?>
<div class="amp-button-holder">
	<a href="<?php echo $url ?>#comments" class="amp-button"><?php _e( 'Comments', 'amphtml' ) ?></a>
</div>
<?php endif;