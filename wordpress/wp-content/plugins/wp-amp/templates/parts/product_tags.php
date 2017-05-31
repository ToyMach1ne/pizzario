<?php
$tag_count  = sizeof( get_the_terms( $this->post->ID, 'product_tag' ) );
echo $this->product->get_tags( ', ',
	'<p class="amphtml-tagged-as">' . _n( 'Tag:', 'Tags:', $tag_count, 'amphtml' ) . ' ', '</p>' );
