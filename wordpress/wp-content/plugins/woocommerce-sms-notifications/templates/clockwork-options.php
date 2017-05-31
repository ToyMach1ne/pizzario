<div class="wrap">
  <div class="left-content">
      
    <div class="icon32"><img src="<?php echo plugins_url( 'images/logo_32px_32px.png', dirname( __FILE__ ) ); ?>" /></div>
    <h2>Clockwork SMS Options</h2>
    
    <form method="post" action="options.php" id="clockwork_options_form">
    
    <?php
    foreach( array_unique( get_settings_errors( 'clockwork_options' ) ) as $error ) {
      if( $error['type'] == 'updated' ) {
        print '<div id="message" class="updated fade"><p><strong>' . $error['message'] . '</strong></p></div>';        
      } else {
        print '<div id="message" class="error"><p><strong>' . $error['message'] . '</strong></p></div>';                
      }
    }
    
    settings_fields( 'clockwork_options' );
    do_settings_sections( 'clockwork' );
    submit_button();
    ?>
    
    </form>
    
  </div>
  
  <div class="right-content">
    <div class="innerbox">
      <h2>Get Support</h2>
      
      <p>First, test your Clockwork plugins are working correctly. This will produce a log file you can send to us to help diagnose your issue.</p>
      
      <p>Enter your mobile number in international format below. It will attempt to send you a test message. If the message sends successfully, it will use 5p of your Clockwork credit.</p>
      
      <form method="admin.php?page=clockwork_test_message" method="post" accept-charset="utf-8">      
      <input type="hidden" name="page" value="clockwork_test_message">
      <p><input type="text" size="14" maxlength="14" class="clockwork_number_field" name="to" value="" placeholder="Your mobile number"> <button type="submit" class="button">Test</a></p>
      </form>
    </div>
      
    <img src="<?php echo plugins_url( 'images/badrobot.png', dirname( __FILE__ ) ); ?>" />
  </div>

</div>