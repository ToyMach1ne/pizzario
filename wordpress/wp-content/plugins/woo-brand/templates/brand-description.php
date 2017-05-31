<?php global $woocommerce; 
if(get_option('pw_woocommerce_brands_image_list')=="yes" || get_option('pw_woocommerce_brands_desc_list')=="yes"){ ?>

	<div class="term-description brand-description">

		<?php if ( $thumbnail ) : 
			if($url!=""){
				
			$ratio = get_option( 'pw_woocommerce_brands_image_list_image_size', "150:150" );

			list( $width, $height ) = explode( ':', $ratio );					
			
			?>
				<a href="<?php echo $url;?>"><img style="width: <?php echo $width;?>px;height: <?php echo $height;?>px" src="<?php echo $thumbnail; ?>" alt="<?php echo $name; ?>"></a>
			<?php }
			else { ?>
				<img src="<?php echo $thumbnail; ?>" alt="<?php echo $name; ?>"/>
			<?php } ?>
		<?php endif;
		
			if(get_option('pw_woocommerce_brands_desc_list')=="yes"){ 
				if($url!=""){
				?>
					<div class="text">
						<a href="<?php echo $url;?>">
							<?php echo wpautop( wptexturize( term_description() ) ); ?>
						</a>
					</div>
				<?php }
				else {?>
					<div class="text">
						<?php echo wpautop( wptexturize( term_description() ) ); ?>
					</div>
				<?php } ?>
		<?php } ?>

	</div>
<?php }?>