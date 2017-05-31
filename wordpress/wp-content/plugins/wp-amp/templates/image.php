<?php
/**
 * The Template for render AMP HTML page images
 *
 * This template can be overridden by copying it to yourtheme/wp-amp/image.php.
 *
 * @var $this AMPHTML_Template
*/
if ( isset( $element[0] ) ): ?>
	<p>
 		<amp-img src="<?php echo $element[0] ?>" layout="responsive"
 			<?php echo ( isset( $element[1] ) && $element[1] ) ? 'width="' . $element[1] . '"' : ''; ?>
 			<?php echo ( isset( $element[2] ) && $element[2] ) ? 'height="' . $element[2] . '"' : ''; ?>
		    <?php echo $element['alt'] ? sprintf( "alt='%s'", esc_attr( $element['alt'] ) ) : ''; ?>>
 		</amp-img>
 	</p>
<?php endif; ?> 