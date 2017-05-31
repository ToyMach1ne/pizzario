jQuery(document).ready( function($) {
  
  if( $('input#clockwork_from').length > 0 ) {
    
    // Cut to 11 characters on blur if it contains alphanumeric characters
    $('input#clockwork_from').blur( function(e) {
      $('input#clockwork_from').val( trimToValid( $('input#clockwork_from').val() ) );
    });
    
    // Do the same on form submit
    $('form#clockwork_options_form').submit( function(e) {
      $('input#clockwork_from').val( trimToValid( $('input#clockwork_from').val() ) );      
    });
    
    // Input mask
    $("input#clockwork_from").keypress( function(e) {
      original_value = $(this).val();
      character = String.fromCharCode(e.keyCode ? e.keyCode : e.which);
      
      if( !character.match( /[0-9A-Za-z]/ ) ) {
        return false;
      }
    });
    
  }
  
});

function trimToValid( val ) {
  if( val.length > 11 && val.match(/[^d]/) ) {
    return val.substring( 0, 11 );
  }
  
  return val;
}