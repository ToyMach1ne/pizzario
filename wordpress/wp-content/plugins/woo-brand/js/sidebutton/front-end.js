// JavaScript Document



jQuery(document).ready(function() {



	jQuery('.scroll-pane').jScrollPane();



	function filterResultsbrands(letter){

		jQuery('.jspPane , .jspDrag').css('top','0');

		//confirm(letter);

		if(letter=="ALL"){

			jQuery('.brand-item-eb').removeClass('hidden').addClass('visible');

			return false;

		}

		jQuery('.brand-item-eb').removeClass('visible').addClass('hidden');

		if(letter=="123"){

			var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

			jQuery('.brand-item-eb').filter(function() {

				return arr_0_9.indexOf(jQuery(this).text().charAt(0).toUpperCase()) != -1;

			}).removeClass('hidden').addClass('visible');

		}else

		{

			jQuery('.brand-item-eb').filter(function() {

				return jQuery(this).text().charAt(0).toUpperCase() === letter;

			}).removeClass('hidden').addClass('visible');

		}

	};

	filterResultsbrands('ALL');

	jQuery('.alphabet-item-eb').on('click',function(){

		var letter = jQuery(this).text();      

		jQuery('.alphabet-item-eb').removeClass('active-letter_brands');

		jQuery(this).addClass('active-letter_brands');     

		filterResultsbrands(letter);        

	});





	var $p =  jQuery('.brand-item-eb');

	var flag=false;

	jQuery('.alphabet-item-eb').addClass(function(){

		var s = this.textContent;

		if(s=="ALL")

			return false;

			

		if(s=="123"){

			var arr_0_9=["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

			

			$p.filter(function(){

			   if(arr_0_9.indexOf(this.textContent.charAt(0).toUpperCase()) != -1)

			   {

				   flag=true;

				   return '';

			   }

			});

			if(flag==false)

			{

				return 'grey';

			}

		}	

		else{	

			return $p.filter(function(){

			   return this.textContent.charAt(0).toUpperCase() === s.toUpperCase()

				   }).length ? '' : 'grey';

		}

	});



});