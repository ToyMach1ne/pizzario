// JavaScript Document

var ajaxcache={};
var current_cache_id={};
jQuery(function(jQuery) {	
	
	jQuery('.pw_brand_category_filter').change(function(){
		var $rand_id=jQuery(this).attr('data-id');
		var $value=jQuery(this).val();
		var form_id="#pw_brand_form"+$rand_id;
				
		var cache_id=$rand_id+"_pw_brand_result_"+jQuery(form_id).serialize();
		if (!ajaxcache[cache_id]) {
			
			var height=jQuery('#pw_brand_result'+$rand_id).height();
			jQuery('#pw_brand_loadd'+$rand_id).height(height);
			
			jQuery('#pw_brand_loadd'+$rand_id).css('display','block');
			
			jQuery('#pw_brand_result'+$rand_id).css('display','none');
			
			
			ajaxcache[cache_id]=jQuery.ajax ({
				type: "POST",
				url: parameters.ajaxurl,
				data:  jQuery(form_id).serialize()+"&action=pw_brand_fetch_brands"
			});
		}
		
		ajaxcache[cache_id].success(function(data){
			
			jQuery('#pw_brand_loadd'+$rand_id).css('display','none');
			jQuery('#pw_brand_result'+$rand_id).css('display','block');
			jQuery('#pw_brand_result'+$rand_id).html(data);
		});
		
	});
	
	jQuery('.pw_brand_category_filter_product').change(function(){
		var $rand_id=jQuery(this).attr('data-id');
		var $value=jQuery(this).val();
		var form_id="#pw_brand_form"+$rand_id;
				
		var cache_id=$rand_id+"_pw_brand_result_"+jQuery(form_id).serialize();
		if (!ajaxcache[cache_id]) {
			
			var height=jQuery('#pw_brand_result'+$rand_id).height();
			jQuery('#pw_brand_loadd'+$rand_id).height(height);
			jQuery('#pw_brand_loadd'+$rand_id).css('display','block');
			jQuery('#pw_brand_result'+$rand_id).css('display','none');
			
			ajaxcache[cache_id]=jQuery.ajax ({
				type: "POST",
				url: parameters.ajaxurl,
				data:  jQuery(form_id).serialize()+"&action=pw_brand_fetch_products"
			});
		}
		
		ajaxcache[cache_id].success(function(data){
			
			jQuery('#pw_brand_loadd'+$rand_id).css('display','none');
			jQuery('#pw_brand_result'+$rand_id).css('display','block');
			jQuery('#pw_brand_result'+$rand_id).html(data);
			
		});
		
	});
	
	jQuery('.wb-wb-allview-letters').click(function(e) {
      	e.preventDefault();
		//get the full url - like mysitecom/index.htm#home
        var full_url = this.href;

        //split the url by # and get the anchor target name - home in mysitecom/index.htm#home
        var parts = full_url.split("#");
        var trgt = parts[1];

        var aTag = jQuery("div[id='"+ trgt +"']");
  		jQuery('html,body').animate({scrollTop: aTag.offset().top},'slow');
    });
	
	jQuery('.pw_brand_category_filter_checkbox').click(function(e){  
		e.preventDefault();
		
		if(jQuery(this).find('input').val()=='0')
		{
			jQuery('.pw_brand_category_filter_checkbox').find('input').each(function(index, element) {
                if(jQuery(this).attr('id')!='pw_brand_category_filter_all')
				{
					jQuery(this).prop('checked',false);
					jQuery(this).parent().removeClass('pw-active-filter');
				}
            });
			
			if(!jQuery('#pw_brand_category_filter_all').is(':checked'))
			{
				jQuery('#pw_brand_category_filter_all').prop('checked',true);
				jQuery('#pw_brand_category_filter_all').parent().addClass('pw-active-filter');
			}
			
			
		}else
		{
			jQuery('#pw_brand_category_filter_all').prop('checked',false);
			jQuery('#pw_brand_category_filter_all').parent().removeClass('pw-active-filter');
			
			if(jQuery(this).find('input').is(':checked'))
			{
				jQuery(this).find('input').prop('checked',false);
				jQuery(this).removeClass('pw-active-filter');
			}
			else
			{
				jQuery(this).find('input').prop('checked',true);
				jQuery(this).addClass('pw-active-filter');
			}
				
			
		}
		
		flag=false;
		jQuery('.pw_brand_category_filter_checkbox').each(function(index, element) {
		
			if(jQuery(this).find('input').is(':checked'))
				flag=true;
		});
		
		
		if(!flag)
		{
			jQuery('#pw_brand_category_filter_all').prop('checked',true);
			jQuery('#pw_brand_category_filter_all').parent().addClass('pw-active-filter');
			jQuery(this).removeClass('pw-active-filter');
		}
		
		var $rand_id=jQuery(this).find('input').attr('data-id');
		var $value=jQuery(this).find('input').val();
		var form_id="#pw_brand_form"+$rand_id;
				
		var cache_id=$rand_id+"_pw_brand_result_"+jQuery(form_id).serialize();
		if (!ajaxcache[cache_id]) {
			
			var height=jQuery('#pw_brand_result'+$rand_id).height();
			jQuery('#pw_brand_loadd'+$rand_id).height(height);
			jQuery('#pw_brand_loadd'+$rand_id).css('display','block');
			jQuery('#pw_brand_result'+$rand_id).css('display','none');
			
			ajaxcache[cache_id]=jQuery.ajax ({
				type: "POST",
				url: parameters.ajaxurl,
				data:  jQuery(form_id).serialize()+"&action=pw_brand_fetch_brands"
			});
		}
		
		ajaxcache[cache_id].success(function(data){
			
			jQuery('#pw_brand_loadd'+$rand_id).css('display','none');
			jQuery('#pw_brand_result'+$rand_id).css('display','block');
			jQuery('#pw_brand_result'+$rand_id).html(data);

		});
		
	});
	
});