
function dhvc_woo_init() {
	jQuery(document).on('click','.dhvc-woo-quickview a',function(e){
		e.stopPropagation();
		e.preventDefault();
		var $this = jQuery(this);
		$this.parent().addClass('loading');
		jQuery.post(dhvcWooL10n.ajax_url,{
			action: 'dhvc_woo_product_quickview',
			product_id: jQuery(this).data('product_id')
		},function(respon){
			$this.parent().removeClass('loading');
			var $modal = jQuery(respon);
			jQuery('body').append($modal);
			jQuery( document.body ).trigger( 'dhvc_woo_before_show_modal', [ $modal, $this ] );
			$modal.modal('show');
			$modal.find('.variations_form').wc_variation_form();
			$modal.find('.variations select').change();
			$modal.on('hidden.bs.modal',function(){
				$modal.remove();
			});
			jQuery( document.body ).trigger( 'dhvc_woo_after_show_modal', [ $modal, $this ] );
		});
	});
	jQuery(".dhvc-woo-ordering select").on('change',function(){
	    jQuery(this).closest("form").submit();
	});

	jQuery('.dhvc-woo-carousel-list').each(
			function() {
				var $this = jQuery(this), defaults = {
					items : 4,
					pagination : false,
					singleItem : false,
					loop: true,
					dots:false,
					nav:false,
					responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:3
				        },
				        1000:{
				            items:5
				        }
				    }
				}, options = jQuery.extend({}, defaults, {
					items : $this.data('items'),
					responsive:{
				        0:{
				            items:1
				        },
				        600:{
				            items:3
				        },
				        1000:{
				            items:$this.data('items')
				        }
				    },
				    dots:$this.data('pagination'),
					pagination : $this.data('pagination')
				});

				$this.owlCarousel(options);
				$this.closest('.dhvc-woo').find('.dhvc-woo-carousel-prev')
						.click(function(e) {
							e.stopPropagation();
							e.preventDefault();
							$this.data('owlCarousel').prev();
						});
				$this.closest('.dhvc-woo').find('.dhvc-woo-carousel-next')
						.click(function(e) {
							e.stopPropagation();
							e.preventDefault();
							$this.data('owlCarousel').next();
						});
	});
	jQuery('.dhvc-woo-masonry-list').each(function() {
		var $this = jQuery(this);
		
		$this.isotope({
			itemSelector : '.dhvc-woo-item',
			transitionDuration : '0.8s',
			masonry : {
				'gutter' : $this.data('masonry-gutter')
			}
		});

		window.setTimeout(function(){
				$this.isotope('layout');
		},2000);
	});

	jQuery(document).on('click','.dhvc-woo-filter',function(e) {

		e.stopPropagation();
		e.preventDefault();

		var $this = jQuery(this), $container = $this.closest('.dhvc-woo').find('.dhvc-woo-masonry-list');
		// don't proceed if already selected
		if ($this.hasClass('selected')) {
			return false;
		}
		var filters = $this.closest('ul');
		filters.find('.selected').removeClass('selected');
		$this.addClass('selected');

		var options = {
			layoutMode : 'masonry',
			transitionDuration : '0.8s',
			'masonry' : {
				'gutter' : $container.data('masonry-gutter')
			}
		}, 
		key = filters.attr('data-option-key'), 
		value = $this.attr('data-option-value');

		value = value === 'false' ? false : value;
		options[key] = value;

		$container.isotope(options);
	});

}

jQuery(document).ready(function($) {
	dhvc_woo_init();
	$('body').bind( 'added_to_cart',function(){
		if(window.fragments){
			jQuery('.dhvc-woo-masonry-list').each(function() {
				var $this = jQuery(this);
				$this.isotope('layout');
			});
		}
	});
});

