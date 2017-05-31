<div class="wrap">
  <div class="left-content">
      
    <div class="icon32"><img src="<?php echo plugins_url( 'images/logo_32px_32px.png', dirname( __FILE__ ) ); ?>" /></div>
    <h2>Clockwork SMS – Send A Test Message</h2>
    
    <?php if( $_GET['to'] == '' ) { ?>
    <p>You need to enter a mobile number to send a test to.</p>
    <?php } else { ?>
    
    <p>You should now have received a text message to <?php print $_GET['to']; ?>. If you have not received this, copy and paste the contents of the textbox below into a support request.</p>
    
    <p><a href="<?php echo self::SUPPORT_URL; ?>" class="button" target="_blank">Contact Clockwork</a></p>
    
    <br />
    <textarea name="log" rows="25" cols="90" id="log"><?php print $data['log']; ?></textarea>
    <?php } ?>
    
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