<div class="wrap">
  <div class="left-content">
      
    <div class="icon32"><img src="<?php echo plugins_url( 'images/logo_32px_32px.png', dirname( __FILE__ ) ); ?>" /></div>
    <h2>WooCommerce SMS Options</h2>
    
    <?php $errors = get_settings_errors(); ?>
    <?php if( isset( $errors ) ) { ?>
      <?php foreach( $errors as $e ) { ?>
      <div id="message" class="<?php print $e['type']; ?>">
        <p><?php _e( $e['message'] ) ?></p>
      </div>    
      <?php } ?>
    <?php } ?>
    
    <form method="post" action="options.php">
		<?php settings_fields('clockwork_woocommerce'); ?>
		<?php do_settings_sections('clockwork_woocommerce'); ?>
    <?php settings_errors('clockwork_woocommerce'); ?>
    <?php submit_button(); ?>
    </form>
    
    <form method="post" action="options.php">
		<?php settings_fields('clockwork_woocommerce_admin_sms'); ?>
		<?php do_settings_sections('clockwork_woocommerce_admin_sms'); ?>
    <?php settings_errors('clockwork_woocommerce_admin_sms'); ?>
    <?php submit_button(); ?>
    </form>
    
    <form method="post" action="options.php">
		<?php settings_fields('clockwork_woocommerce_customer_sms'); ?>
		<?php do_settings_sections('clockwork_woocommerce_customer_sms'); ?>
    <?php settings_errors('clockwork_woocommerce_customer_sms'); ?>
    <?php submit_button(); ?>
    </form>

  </div>  
  
  <div class="right-content">      
    <img src="<?php echo plugins_url( 'images/badrobot.png', dirname( __FILE__ ) ); ?>" style="margin-top: 20px;" />
  </div>
</div>
