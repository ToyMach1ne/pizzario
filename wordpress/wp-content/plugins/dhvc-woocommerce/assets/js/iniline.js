
(function($) {
	 if(_.isUndefined(window.vc)) window.vc = {};
	 $(window).load(function(){
		 $( document ).ajaxComplete(function(e) {
			 vc.frame_window.vc_iframe.addActivity(function(){
			  this.dhvc_woo_init();
	     	});
		 });
	 })
})(window.jQuery);