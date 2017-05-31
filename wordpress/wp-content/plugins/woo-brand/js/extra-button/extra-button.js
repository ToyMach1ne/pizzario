// JavaScript Document
jQuery(document).ready(function() {
	
	function init_scroll_eb(){
		var $scrollbar = jQuery('.eb-scroll');
		$scrollbar.tinyscrollbar();
		var scrollbar = $scrollbar.data("plugin_tinyscrollbar")
		scrollbar.update();
		setTimeout(function(){
		 	scrollbar.update();
		 },100); 
		return false;
	}
	
	function filterResultsbrands(letter){
		init_scroll_eb();
		if(letter=="ALL"){

			jQuery('.brand-item-eb').removeClass('hidden').addClass('visible');

			return false;

		}

		jQuery('.brand-item-eb').removeClass('visible').addClass('hidden');

		if(letter=="123"){

			var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

			jQuery('.brand-item-eb').filter(function() {
			
				//confirm(jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9));	

				return jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9)!= -1;

			}).removeClass('hidden').addClass('visible');

		}else

		{

			jQuery('.brand-item-eb').filter(function() {

				return jQuery(this).text().charAt(0).toUpperCase() === letter;

			}).removeClass('hidden').addClass('visible');

		}

	};



	jQuery('.wb-alphabet-item-eb').on('click',function(){

		var letter = jQuery(this).text();      

		jQuery('.wb-alphabet-item-eb').removeClass('active-letter-eb');

		jQuery(this).addClass('active-letter-eb');     

		filterResultsbrands(letter);        

	});


	jQuery( ".wb-alphabet-item-eb" ).each(function() {
		var letter=jQuery(this);
		
		
		if(jQuery(this).text().toUpperCase()=='ALL')
		{
			
		}else if (jQuery(this).text()=='123')
		{
			var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
			var flag=false;
			jQuery(".brand-item-eb" ).each(function() {
				if(jQuery.inArray(jQuery(this).text().charAt(0).toUpperCase(),arr_0_9)!= -1)
				{
					flag=true;
					return false;
					//confirm(letter.text());
				}
			});
			if(flag==false)
			{
				letter.addClass('wb-invis-item');
			}
			
		}else
		{
			var flag=false;
			jQuery(".brand-item-eb" ).each(function() {
				if(jQuery(this).text().charAt(0).toUpperCase()==letter.text().charAt(0).toUpperCase())
				{
					flag=true;
					return false;
					//confirm(letter.text());
				}
			});
			if(flag==false)
			{
				letter.addClass('wb-invis-item');
			}
		}
	});

      jQuery(".pw-stick").click(function(){
		 jQuery( ".pw-stick" ).removeClass( "pw-active-stick" );
		 jQuery(this).addClass( "pw-active-stick" );	 
			filterResultsbrands('ALL');
		//var id=jQuery(this).attr('id');
		var distance=window.innerHeight - jQuery(this).position().top;
		var height=jQuery('.pw_stick_brands').height();
		jQuery('.pw_stick_brands').css('top',jQuery(this).position().top);
		
		if(height>distance)
		{
			jQuery('.pw_stick_brands').css('top',(jQuery(this).position().top)-((height-distance) + 30) );
		}
		 setTimeout(function(){
		 	if (jQuery(".pw_stick_brands").hasClass('pw-active-content')){ 
				jQuery(".pw_stick_brands").removeClass( "pw-active-content" );
				jQuery( ".pw-stick" ).removeClass( "pw-active-stick" );
				
			}
			else if (!jQuery(".pw_stick_brands").hasClass('pw-active-content')){ 
				jQuery('.pw-content').removeClass('pw-active-content');
				jQuery(".pw_stick_brands").addClass( "pw-active-content" );	
			}
		 },300); 
      });
	  jQuery(".pw-content-close").click(function(){ 
	  	jQuery('.pw-content').removeClass('pw-active-content');
		jQuery( ".pw-stick" ).removeClass( "pw-active-stick" );
	  });	

	//var $scrollbar = jQuery(".wt-scrollbarcnt");
	//$scrollbar.tinyscrollbar();
		//jQuery('.scroll-pane').jScrollPane();
});