<?php $buttons = $this->options->get( 'social_share_buttons' ); ?>
<?php if ( $buttons ): ?>
	<div class="social-box">
		<?php foreach ( $buttons as $social_link ): ?>
			<amp-social-share type="<?php echo $social_link ?>"
				<?php if ( 'facebook' == $social_link ) { ?>
					data-param-app_id="145634995501895"
				<?php } ?>
				<?php if ( 'pinterest' == $social_link ) { ?>
					data-do="buttonPin"
				<?php } ?>
				<?php if ( 'whatsapp' == $social_link ) { ?>
					data-share-endpoint="whatsapp://send"
					data-param-text="TITLE - CANONICAL_URL"
				<?php } ?>
			></amp-social-share>
		<?php endforeach; ?>
	</div>
<?php endif; ?>