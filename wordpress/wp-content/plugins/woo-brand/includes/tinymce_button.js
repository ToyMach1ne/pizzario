(function(){
	tinymce.PluginManager.add('woo_brand_shortcodes_button', function( editor, url ) {
        editor.addButton( 'woo_brand_shortcodes_button', {
            text: 'Add Brand',
            icon: false,
            onclick: function() {
				var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
				W = W - 80;
				H = H - 84;
				tb_show( 'Woocommerce Brand Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=flash_sale-shortcodes-form' );
            }
        });
    });

	
	
	jQuery(function(){
	
		/* Social Links */

var social_icons = '<a href="#" class="button flash_sale-shortcodes-toggle-icon-list flash_sale-shortcodes-toggle-social-list" style="float:left;display:block;">Choose icon</a><div class="social-icon"></div>\
<i class="current-social-icon sn-social-icon-"></i>\
<div class="flash_sale-shortcodes-icon-list-holder" style="display:none;">\
<p style="margin-bottom:0px">Icons from <a href="http://zocial.smcllns.com/" target="_blank">Zocial</a> (via <a href="http://fontello.com/" target="_blank">Fontello</a>) and <a href="http://icondock.com/free/vector-social-media-icons" target="_blank">Icon Dock</a></p>\
<ul class="flash_sale-shortcodes-icon-list">\
<li><a href="#"><i class="sn-social-icon-twitter"></i> <span>sn-social-icon-twitter</span></a></li>\
<li><a href="#"><i class="sn-social-icon-facebook"></i> <span>sn-social-icon-facebook</span></a></li>\
<li><a href="#"><i class="sn-social-icon-linkedin"></i> <span>sn-social-icon-linkedin</span></a></li>\
<li><a href="#"><i class="sn-social-icon-pinterest"></i> <span>sn-social-icon-pinterest</span></a></li>\
<li><a href="#"><i class="sn-social-icon-delicious"></i> <span>sn-social-icon-delicious</span></a></li>\
<li><a href="#"><i class="sn-social-icon-paypal"></i> <span>sn-social-icon-paypal</span></a></li>\
<li><a href="#"><i class="sn-social-icon-gplus"></i> <span>sn-social-icon-gplus</span></a></li>\
<li><a href="#"><i class="sn-social-icon-stumbleupon"></i> <span>sn-social-icon-stumbleupon</span></a></li>\
<li><a href="#"><i class="sn-social-icon-fivehundredpx"></i> <span>sn-social-icon-fivehundredpx</span></a></li>\
<li><a href="#"><i class="sn-social-icon-foursquare"></i> <span>sn-social-icon-foursquare</span></a></li>\
<li><a href="#"><i class="sn-social-icon-forrst"></i> <span>sn-social-icon-forrst</span></a></li>\
<li><a href="#"><i class="sn-social-icon-digg"></i> <span>sn-social-icon-digg</span></a></li>\
<li><a href="#"><i class="sn-social-icon-spotify"></i> <span>sn-social-icon-spotify</span></a></li>\
<li><a href="#"><i class="sn-social-icon-reddit"></i> <span>sn-social-icon-reddit</span></a></li>\
<li><a href="#"><i class="sn-social-icon-flickr"></i> <span>sn-social-icon-flickr</span></a></li>\
<li><a href="#"><i class="sn-social-icon-rss"></i> <span>sn-social-icon-rss</span></a></li>\
<li><a href="#"><i class="sn-social-icon-skype"></i> <span>sn-social-icon-skype</span></a></li>\
<li><a href="#"><i class="sn-social-icon-youtube"></i> <span>sn-social-icon-youtube</span></a></li>\
<li><a href="#"><i class="sn-social-icon-vimeo"></i> <span>sn-social-icon-vimeo</span></a></li>\
<li><a href="#"><i class="sn-social-icon-myspace"></i> <span>sn-social-icon-myspace</span></a></li>\
<li><a href="#"><i class="sn-social-icon-amazon"></i> <span>sn-social-icon-amazon</span></a></li>\
<li><a href="#"><i class="sn-social-icon-ebay"></i> <span>sn-social-icon-ebay</span></a></li>\
<li><a href="#"><i class="sn-social-icon-github"></i> <span>sn-social-icon-github</span></a></li>\
<li><a href="#"><i class="sn-social-icon-lastfm"></i> <span>sn-social-icon-lastfm</span></a></li>\
<li><a href="#"><i class="sn-social-icon-soundcloud"></i> <span>sn-social-icon-soundcloud</span></a></li>\
<li><a href="#"><i class="sn-social-icon-tumblr"></i> <span>sn-social-icon-tumblr</span></a></li>\
<li><a href="#"><i class="sn-social-icon-instagram"></i> <span>sn-social-icon-instagram</span></a></li>\
<li style="clear:left"><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/500px.png" alt="" /><span>500px</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/aboutme.png" alt="" /><span>aboutme</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/amazon.png" alt="" /><span>amazon</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/app-store.png" alt="" /><span>app-store</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/bebo.png" alt="" /><span>bebo</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/behance.png" alt="" /><span>behance</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/blogger.png" alt="" /><span>blogger</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/coroflot.png" alt="" /><span>coroflot</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/delicious.png" alt="" /><span>delicious</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/deviant-art.png" alt="" /><span>deviant-art</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/digg.png" alt="" /><span>digg</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/dribbble.png" alt="" /><span>dribbble</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/ebay.png" alt="" /><span>ebay</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/etsy.png" alt="" /><span>etsy</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/facebook.png" alt="" /><span>facebook</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/flickr.png" alt="" /><span>flickr</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/foodspotting.png" alt="" /><span>foodspotting</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/forrst.png" alt="" /><span>forrst</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/foursquare.png" alt="" /><span>foursquare</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/friendfeed.png" alt="" /><span>friendfeed</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/friendster.png" alt="" /><span>friendster</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/github.png" alt="" /><span>github</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/google-plus.png" alt="" /><span>google-plus</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/gowalla.png" alt="" /><span>gowalla</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/hyves.png" alt="" /><span>hyves</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/instagram.png" alt="" /><span>instagram</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/lastfm.png" alt="" /><span>lastfm</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/linkedin.png" alt="" /><span>linkedin</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/metacafe.png" alt="" /><span>metacafe</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/myspace.png" alt="" /><span>myspace</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/photobucket.png" alt="" /><span>photobucket</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/picasa.png" alt="" /><span>picasa</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/pinterest.png" alt="" /><span>pinterest</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/reddit.png" alt="" /><span>reddit</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/rss.png" alt="" /><span>rss</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/scribd.png" alt="" /><span>scribd</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/skype.png" alt="" /><span>skype</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/slashdot.png" alt="" /><span>slashdot</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/slideshare.png" alt="" /><span>slideshare</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/soundcloud.png" alt="" /><span>soundcloud</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/spotify.png" alt="" /><span>spotify</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/stumbleupon.png" alt="" /><span>stumbleupon</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/technorati.png" alt="" /><span>technorati</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/tumblr.png" alt="" /><span>tumblr</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/twitter.png" alt="" /><span>twitter</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/vimeo.png" alt="" /><span>vimeo</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/virb.png" alt="" /><span>virb</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/wordpress.png" alt="" /><span>wordpress</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/yahoo-buzz.png" alt="" /><span>yahoo-buzz</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/yahoo.png" alt="" /><span>yahoo</span></a></li>\
<li><a href="#"><img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/youtube.png" alt="" /><span>youtube</span></a></li>\
</ul>\
</div>';


var fa_icons = '<ul class="flash_sale-shortcodes-icon-list">\
<li><a href="#"><i class="fa-icon-">&#xf000;</i> <span>glass</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf001;</i> <span>music</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf002;</i> <span>search</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf003;</i> <span>envelope</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf004;</i> <span>heart</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf005;</i> <span>star</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf006;</i> <span>star-empty</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf007;</i> <span>user</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf008;</i> <span>film</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf009;</i> <span>th-large</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf00a;</i> <span>th</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf00b;</i> <span>th-list</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf00c;</i> <span>ok</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf00d;</i> <span>remove</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf00e;</i> <span>zoom-in</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf010;</i> <span>zoom-out</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf011;</i> <span>off</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf012;</i> <span>signal</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf013;</i> <span>cog</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf014;</i> <span>trash</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf015;</i> <span>home</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf016;</i> <span>file</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf017;</i> <span>time</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf018;</i> <span>road</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf019;</i> <span>download-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf01a;</i> <span>download</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf01b;</i> <span>upload</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf01c;</i> <span>inbox</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf01d;</i> <span>play-circle</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf01e;</i> <span>repeat</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf021;</i> <span>refresh</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf022;</i> <span>list-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf023;</i> <span>lock</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf024;</i> <span>flag</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf025;</i> <span>headphones</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf026;</i> <span>volume-off</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf027;</i> <span>volume-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf028;</i> <span>volume-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf029;</i> <span>qrcode</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02a;</i> <span>barcode</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02b;</i> <span>tag</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02c;</i> <span>tags</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02d;</i> <span>book</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02e;</i> <span>bookmark</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf02f;</i> <span>print</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf030;</i> <span>camera</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf031;</i> <span>font</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf032;</i> <span>bold</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf033;</i> <span>italic</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf034;</i> <span>text-height</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf035;</i> <span>text-width</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf036;</i> <span>align-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf037;</i> <span>align-center</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf038;</i> <span>align-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf039;</i> <span>align-justify</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf03a;</i> <span>list</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf03b;</i> <span>indent-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf03c;</i> <span>indent-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf03d;</i> <span>facetime-video</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf03e;</i> <span>picture</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf040;</i> <span>pencil</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf041;</i> <span>map-marker</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf042;</i> <span>adjust</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf043;</i> <span>tint</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf044;</i> <span>edit</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf045;</i> <span>share</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf046;</i> <span>check</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf047;</i> <span>move</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf048;</i> <span>step-backward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf049;</i> <span>fast-backward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf04a;</i> <span>backward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf04b;</i> <span>play</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf04c;</i> <span>pause</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf04d;</i> <span>stop</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf04e;</i> <span>forward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf050;</i> <span>fast-forward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf051;</i> <span>step-forward</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf052;</i> <span>eject</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf053;</i> <span>chevron-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf054;</i> <span>chevron-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf055;</i> <span>plus-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf056;</i> <span>minus-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf057;</i> <span>remove-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf058;</i> <span>ok-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf059;</i> <span>question-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf05a;</i> <span>info-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf05b;</i> <span>screenshot</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf05c;</i> <span>remove-circle</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf05d;</i> <span>ok-circle</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf05e;</i> <span>ban-circle</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf060;</i> <span>arrow-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf061;</i> <span>arrow-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf062;</i> <span>arrow-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf063;</i> <span>arrow-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf064;</i> <span>share-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf065;</i> <span>resize-full</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf066;</i> <span>resize-small</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf067;</i> <span>plus</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf068;</i> <span>minus</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf069;</i> <span>asterisk</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf06a;</i> <span>exclamation-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf06b;</i> <span>gift</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf06c;</i> <span>leaf</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf06d;</i> <span>fire</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf06e;</i> <span>eye-open</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf070;</i> <span>eye-close</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf071;</i> <span>warning-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf072;</i> <span>plane</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf073;</i> <span>calendar</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf074;</i> <span>random</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf075;</i> <span>comment</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf076;</i> <span>magnet</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf077;</i> <span>chevron-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf078;</i> <span>chevron-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf079;</i> <span>retweet</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf07a;</i> <span>shopping-cart</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf07b;</i> <span>folder-close</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf07c;</i> <span>folder-open</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf07d;</i> <span>resize-vertical</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf07e;</i> <span>resize-horizontal</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf080;</i> <span>bar-chart</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf081;</i> <span>twitter-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf082;</i> <span>facebook-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf083;</i> <span>camera-retro</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf084;</i> <span>key</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf085;</i> <span>cogs</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf086;</i> <span>comments</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf087;</i> <span>thumbs-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf088;</i> <span>thumbs-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf089;</i> <span>star-half</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf08a;</i> <span>heart-empty</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf08b;</i> <span>signout</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf08c;</i> <span>linkedin-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf08d;</i> <span>pushpin</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf08e;</i> <span>external-link</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf090;</i> <span>signin</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf091;</i> <span>trophy</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf092;</i> <span>github-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf093;</i> <span>upload-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf094;</i> <span>lemon</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf095;</i> <span>phone</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf096;</i> <span>check-empty</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf097;</i> <span>bookmark-empty</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf098;</i> <span>phone-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf099;</i> <span>twitter</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf09a;</i> <span>facebook</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf09b;</i> <span>github</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf09c;</i> <span>unlock</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf09d;</i> <span>credit-card</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf09e;</i> <span>rss</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a0;</i> <span>hdd</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a1;</i> <span>bullhorn</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a2;</i> <span>bell</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a3;</i> <span>certificate</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a4;</i> <span>hand-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a5;</i> <span>hand-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a6;</i> <span>hand-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a7;</i> <span>hand-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a8;</i> <span>circle-arrow-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0a9;</i> <span>circle-arrow-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0aa;</i> <span>circle-arrow-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ab;</i> <span>circle-arrow-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ac;</i> <span>globe</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ad;</i> <span>wrench</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ae;</i> <span>tasks</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0b0;</i> <span>filter</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0b1;</i> <span>briefcase</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0b2;</i> <span>fullscreen</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c0;</i> <span>group</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c1;</i> <span>link</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c2;</i> <span>cloud</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c3;</i> <span>beaker</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c4;</i> <span>cut</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c5;</i> <span>copy</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c6;</i> <span>paper-clip</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c7;</i> <span>save</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c8;</i> <span>sign-blank</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0c9;</i> <span>reorder</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ca;</i> <span>list-ul</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0cb;</i> <span>list-ol</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0cc;</i> <span>strikethrough</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0cd;</i> <span>underline</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ce;</i> <span>table</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d0;</i> <span>magic</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d1;</i> <span>truck</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d2;</i> <span>pinterest</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d3;</i> <span>pinterest-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d4;</i> <span>google-plus-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d5;</i> <span>google-plus</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d6;</i> <span>money</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d7;</i> <span>caret-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d8;</i> <span>caret-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0d9;</i> <span>caret-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0da;</i> <span>caret-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0db;</i> <span>columns</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0dc;</i> <span>sort</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0dd;</i> <span>sort-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0de;</i> <span>sort-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e0;</i> <span>envelope-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e1;</i> <span>linkedin</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e2;</i> <span>undo</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e3;</i> <span>legal</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e4;</i> <span>dashboard</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e5;</i> <span>comment-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e6;</i> <span>comments-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e7;</i> <span>bolt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e8;</i> <span>sitemap</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0e9;</i> <span>umbrella</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ea;</i> <span>paste</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0eb;</i> <span>lightbulb</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ec;</i> <span>exchange</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ed;</i> <span>cloud-download</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0ee;</i> <span>cloud-upload</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f0;</i> <span>user-md</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f1;</i> <span>stethoscope</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f2;</i> <span>suitcase</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f3;</i> <span>bell-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f4;</i> <span>coffee</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f5;</i> <span>food</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f6;</i> <span>file-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f7;</i> <span>building</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f8;</i> <span>hospital</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0f9;</i> <span>ambulance</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0fa;</i> <span>medkit</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0fb;</i> <span>fighter-jet</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0fc;</i> <span>beer</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0fd;</i> <span>h-sign</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf0fe;</i> <span>plus-sign-alt</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf100;</i> <span>double-angle-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf101;</i> <span>double-angle-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf102;</i> <span>double-angle-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf103;</i> <span>double-angle-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf104;</i> <span>angle-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf105;</i> <span>angle-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf106;</i> <span>angle-up</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf107;</i> <span>angle-down</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf108;</i> <span>desktop</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf109;</i> <span>laptop</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf10a;</i> <span>tablet</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf10b;</i> <span>mobile-phone</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf10c;</i> <span>circle-blank</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf10d;</i> <span>quote-left</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf10e;</i> <span>quote-right</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf110;</i> <span>spinner</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf111;</i> <span>circle</span></a></li>\
<li><a href="#"><i class="fa-icon-">&#xf112;</i> <span>reply</span></a></li>\
</ul>';
	
		/* The form */
		var form = jQuery('<style type="text/css">\
#TB_ajaxContent { padding: 0 0 30px 0; width: 100%!important; overflow: hidden; }\
.flash_sale-shortcodes-form { display: block; overflow: hidden; height: 100%; position: relative; font-family: Helvetica, Arial; color: #333; font-size: 12px; text-align: left; background: #FFF; }\
.flash_sale-shortcodes-form a { text-decoration: none; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-top { overflow: auto; height: 100%; position: relative; padding: 0 0 0 120px; margin: 0 0 0 0; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types { position: absolute; top: 0px; left: 0px; width: 120px; height: 100%; min-height: 100%; background: #f9f9f9; border-right: 1px solid #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types .flash_sale-shortcodes-form-title { display: block; padding: 14px 20px 14px 40px; position: relative; font-size: 12px; line-height: 14px; color: #333; border-bottom: 1px solid #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types .flash_sale-shortcodes-form-title img { position: absolute; top: 13px; left: 12px; } \
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul { list-style: none; margin: 0px; padding: 0px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul li { display: block; border-bottom: 1px solid #EEE; margin: 0px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul li.active { width: 121px; font-weight: bold; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul li a { display: block; padding: 14px 20px; font-size: 12px; color: #333; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul li.active a,  .flash_sale-shortcodes-form .flash_sale-shortcodes-form-types ul li a:hover { background: #FFF; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs { background: #FFF; padding: 20px 20px 70px 20px; overflow: hidden; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tab { display: none; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs h2 { display: block; padding: 0 10px 10px 10px; line-height: normal; font-size: 18px; margin: 0 0 10px 0; border-bottom: 1px solid #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-fields { overflow: auto; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table { width: 100%; border: none; font-size: 12px; text-align: left; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table th,  .flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td { padding: 14px 10px 14px 0; border-bottom: 1px solid #EEE; vertical-align: top; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table th { font-weight: normal; text-align: left; width: 120px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table th label { padding: 5px 0 0 10px; display: block; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td input, .flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td textarea { border: 1px solid #DDD; box-shadow: inset 1px 1px 4px #f9f9f9; padding: 6px 8px; font-family: Helvetica; font-size: 12px; color: #888; width: 80%; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td .wp-picker-container input[type="text"] { width: 80px!important; margin: 0 5px 0 0; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td .wp-picker-container input.button { width: auto!important; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td textarea { height: 100px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td textarea:disabled { background: #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td input:focus, .flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td textarea:focus { color: #333; border-color: #BBB; background: #FFF; outline: none; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-form-tabs table td span.tip { display: block; padding: 5px 0; color: #999; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-submit { clear: both; height: 70px; position: absolute; bottom: 0px; left: 0px; width: 100%; background: #EEE; border-top: 1px solid #DDD; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-submit .button-primary { position: absolute; right: 20px; top: 20px; }\
.flash_sale-shortcodes-form .current-icon, .flash_sale-shortcodes-form .current-social-icon { margin: 0 0 0 10px; font-size: 14px; width: 24px; text-align:center; height: 24px; line-height: 24px; }\
.flash_sale-shortcodes-toggle-social-list { margin: 0 0 20px 0; }\
.flash_sale-shortcodes-form .social-icon, .flash_sale-shortcodes-form .current-social-icon { margin: 0 0 0 10px; display: block; float: left; }\
.flash_sale-shortcodes-form .current-social-icon { display: none; }\
.flash_sale-shortcodes-form .social-icon img { width: 21px; height: 21px; margin: 1px 0 0 0; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-social-link-field { float: left; width: 220px!important; margin: 0 0 0 10px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list-holder { clear: both; overflow: hidden; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list { padding: 10px 0 0 0; display: block; list-style: none; margin: 0px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li { display: block; float: left; width: 40px; height: 40px; margin: 0 10px 10px 0; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li a { display: block; float: left; width: 40px; height: 40px; text-align: center; line-height: 40px; background: #f9f9f9; border: 1px solid #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li a:hover { border: 1px solid #CCC; box-shadow: 1px 1px 2px #EEE; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li a i { font-size: 14px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li a img { width: 20px; height: 20px; margin-top: 10px; }\
.flash_sale-shortcodes-form .flash_sale-shortcodes-icon-list li a span { display: none; }\
.flash_sale-shortcodes-form .column-structures { display: block; padding: 0 0 0 10px; }\
.flash_sale-shortcodes-form .column-structures a { display: block; width: 100px; height: 40px; float: left; margin: 0 10px 20px 0; }\
.flash_sale-shortcodes-form .column-structures a span { float:left; position: relative; display: block; height: 40px; overflow: hidden; }\
.flash_sale-shortcodes-form .column-structures a i { background: #EEE; position: absolute; left: -2px; top: 0px; display: block; height: 40px; line-height: 40px; font-size: 14px; text-align: center; width: 100%; }\
.flash_sale-shortcodes-form .column-structures a:hover i, .flash_sale-shortcodes-form .column-structures a.active i { background: #999; color: #FFF; }\
.flash_sale-shortcodes-form .column-structures label { margin: 0 0 14px 0; display: block; font-size: 14px; }\
\
.flash_sale-shortcodes-form .column-structuress { display: block; padding: 0 0 0 10px; }\
.flash_sale-shortcodes-form .column-structuress a { display: block; width: 100px; height: 40px; float: left; margin: 0 10px 20px 0; }\
.flash_sale-shortcodes-form .column-structuress a span { float:left; position: relative; display: block; height: 40px; overflow: hidden; }\
.flash_sale-shortcodes-form .column-structuress a i { background: #EEE; position: absolute; left: -2px; top: 0px; display: block; height: 40px; line-height: 40px; font-size: 14px; text-align: center; width: 100%; }\
.flash_sale-shortcodes-form .column-structuress a:hover i, .flash_sale-shortcodes-form .column-structuress a.active i { background: #999; color: #FFF; }\
.flash_sale-shortcodes-form .column-structuress label { margin: 0 0 14px 0; display: block; font-size: 14px; }\
\
.flash_sale-shortcodes-form .column-structuresss { display: block; padding: 0 0 0 10px; }\
.flash_sale-shortcodes-form .column-structuresss a { display: block; width: 100px; height: 40px; float: left; margin: 0 10px 20px 0; }\
.flash_sale-shortcodes-form .column-structuresss a span { float:left; position: relative; display: block; height: 40px; overflow: hidden; }\
.flash_sale-shortcodes-form .column-structuresss a i { background: #EEE; position: absolute; left: -2px; top: 0px; display: block; height: 40px; line-height: 40px; font-size: 14px; text-align: center; width: 100%; }\
.flash_sale-shortcodes-form .column-structuresss a:hover i, .flash_sale-shortcodes-form .column-structuresss a.active i { background: #999; color: #FFF; }\
.flash_sale-shortcodes-form .column-structuresss label { margin: 0 0 14px 0; display: block; font-size: 14px; }\
\
.flash_sale-shortcodes-form .clearfix { clear: both; }\
#flash_sale-shortcodes-form-tab_tabs td label,\
#flash_sale-shortcodes-form-tab_accordion td label { display: block; padding: 5px 0 10px 0; color: #999; }\
#flash_sale-shortcodes-form-tab_tabs input,\
#flash_sale-shortcodes-form-tab_accordion input,\
#flash_sale-shortcodes-form-tab_accordion .flash_sale-shortcodes-toggle-icon-list { margin: 0 0 10px 0; }\
</style>\
		<div id="flash_sale-shortcodes-form"><div class="flash_sale-shortcodes-form">\
		<div class="flash_sale-shortcodes-form-top">\
		<div class="flash_sale-shortcodes-form-types">\
			<ul>\
				<li class="flash_sale-shortcodes-form-type-button active" type="a-z-view"><a href="#">A-Z View</a></li>\
				<li class="flash_sale-shortcodes-form-type-social" type="all_views"><a href="#">Brands List</a></li>\
				<li class="flash_sale-shortcodes-form-type-columns" type="carousel"><a href="#">Brands Carousel</a></li>\
				<li class="flash_sale-shortcodes-form-type-columns" type="thumbnails"><a href="#">Brands Thumbnail</a></li>\
				<li class="flash_sale-shortcodes-form-type-columns" type="product_carousel"><a href="#">Products Carousel By Brands</a></li>\
				<li class="flash_sale-shortcodes-form-type-social" type="product_grid"><a href="#">Products Grid By Brands</a></li>\
				<li class="flash_sale-shortcodes-form-type-social" type="filter_brand"><a href="#">Products List Category and Brand`s Fillter</a></li>\
			</ul>\
		</div><!-- end types -->\
		<div class="flash_sale-shortcodes-form-tabs">\
<!-- A-z View -->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_a-z-view" style="display:block">\
				<h2>Add A-z View Shortcode</h2>\
				<div class="flash_sale-shortcodes-form-fields" style="height:500px;">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Filter Style</label></th>\
						<td>\
							<select fieldname="pw_style" >\
								<option value="wb-filter-style1">Style 1</option>\
								<option value="wb-filter-style2">Style 2</option>\
								<option value="wb-filter-style3">Style 3</option>\
								<option value="wb-filter-style4">Style 4</option>\
								<option value="wb-filter-style5">Style 5</option>\
								<option value="wb-filter-style6">Style 6</option>\
								<option value="wb-filter-style7">Style 7</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Brand List Style</label></th>\
						<td>\
							<select fieldname="pw_brand_list_style" >\
								<option value="wb-brandlist-style1">Style 1</option>\
								<option value="wb-brandlist-style2">Style 2</option>\
								<option value="wb-brandlist-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Except Brand(s)</label></th>\
						<td>\
							<select class="select_brand_single chosen-select_brand_n" fieldname="pw_except_brand" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Only Featured Brands</label></th>\
						<td>\
							<select fieldname="pw_featured">\
								<option value="no">No</option>\
								<option value="yes">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Count Of Brand</label></th>\
						<td>\
							<select fieldname="pw_show_count">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Hide Empty Brands</label></th>\
						<td>\
							<select fieldname="pw_hide_empty_brands">\
								<option value="1">Yes</option>\
								<option value="0">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Scroll Height </label></th>\
						<td>\
							<input type="number" value="" fieldname="pw_scroll_height" placeholder="300" style="width: 30%;" /> px\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end A-z View -->\
<!-- Brand Carousel -->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_carousel">\
				<h2>Add Brand Carousel</h2>\
				<div class="flash_sale-shortcodes-form-fields">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Brand(s)</label></th>\
						<td>\
							<select class="chosen-select_brand select_brand" fieldname="pw_brand" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Except Brand(s)</label></th>\
						<td>\
							<select  class="select_brand_single chosen-select_brand_n" fieldname="pw_except_brand" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Only Featured Brands</label></th>\
						<td>\
							<select fieldname="pw_featured">\
								<option value="no">No</option>\
								<option value="yes">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Count Of Brands</label></th>\
						<td>\
							<select fieldname="pw_show_count">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Image</label></th>\
						<td>\
							<select fieldname="pw_show_image">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Image Size</label></th>\
						<td>\
							<select fieldname="pw_show_image_size">\
								<option value="thumb">Thumbnail</option>\
								<option value="full">Full</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Title</label></th>\
						<td>\
							<select fieldname="pw_show_title">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Tooltip</label></th>\
						<td>\
							<select fieldname="pw_tooltip">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Item Style</label></th>\
						<td>\
							<select fieldname="pw_style">\
								<option value="wb-car-style1">Style 1</option>\
								<option value="wb-car-style2">Style 2</option>\
								<option value="wb-car-style3">Style 3</option>\
								<option value="wb-car-style4">Style 4</option>\
								<option value="wb-car-style5">Style 5</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Round Corner</label></th>\
						<td>\
							<select fieldname="pw_round_corner">\
								<option value="wb-car-no">No</option>\
								<option value="wb-car-round">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Controller/Pagination Style</label></th>\
						<td>\
							<select fieldname="pw_carousel_style">\
								<option value="wb-carousel-style1">Style 1</option>\
								<option value="wb-carousel-style2">Style 2</option>\
								<option value="wb-carousel-style3">Style 3</option>\
								<option value="wb-carousel-style4">Style 4</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Carousel Skin</label></th>\
						<td>\
							<select fieldname="pw_carousel_skin_style">\
								<option value="wb-carousel-skin-dark">Dark</option>\
								<option value="wb-carousel-skin-light">Light</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Item Width</label></th>\
						<td><input type="number" value="" fieldname="pw_item_width" style="width: 30%;" /> px</td>\
					</tr>\
					<tr>\
						<th><label>Item Marrgin</label></th>\
						<td><input type="number" value="" fieldname="pw_item_marrgin" style="width: 30%;" /> px</td>\
					</tr>\
					<tr>\
						<th><label>Slide direction</label></th>\
						<td>\
							<select fieldname="pw_slide_direction">\
								<option value="vertical">Vertical</option>\
								<option value="horizontal">Horizontal</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Pagination</label></th>\
						<td>\
							<select fieldname="pw_show_pagination">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Control</label></th>\
						<td>\
							<select fieldname="pw_show_control">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Item Per View</label></th>\
						<td><input type="number" value="" fieldname="pw_item_per_view" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Item Per Slide</label></th>\
						<td><input type="number" value="" fieldname="pw_item_per_slide" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Slide Speed</label></th>\
						<td>\
							<select fieldname="pw_slide_speed">\
								<option value="1000">1 sec</option>\
								<option value="2000">2 sec</option>\
								<option value="3000">3 sec</option>\
								<option value="4000">4 sec</option>\
								<option value="5000">5 sec</option>\
								<option value="6000">6 sec</option>\
								<option value="7000">7 sec</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Auto play</label></th>\
						<td>\
							<select fieldname="pw_auto_play">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end Brand Carousel -->\
<!--Brand Thumbnails-->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_thumbnails">\
				<h2>Add Shortcode Thumbnails</h2>\
				<div class="flash_sale-shortcodes-form-fields">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Brands</label></th>\
						<td>\
							<select class="chosen-select_brand select_brand" fieldname="pw_brand" multiple="multiple" placeholder="None">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Except Brand(s)</label></th>\
						<td>\
							<select class="select_brand_single chosen-select_brand_n" fieldname="pw_except_brand" multiple="multiple" placeholder="None">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Count Of Item</label></th>\
						<td>\
							<input type="number" value="" fieldname="pw_count_of_number" placeholder="All" style="width: 30%;" />\
						</td>\
					</tr>\
					<tr>\
						<th><label>Order By</label></th>\
						<td>\
							<select fieldname="pw_order_by">\
								<option value="name">ASC</option>\
								<option value="count">DESC</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Only Featured Brands</label></th>\
						<td>\
							<select fieldname="pw_featured">\
								<option value="no">No</option>\
								<option value="yes">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Hide Empty Brands</label></th>\
						<td>\
							<select fieldname="pw_hide_empty_brands">\
								<option value="1">Yes</option>\
								<option value="0">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Image Size</label></th>\
						<td>\
							<select fieldname="pw_show_image_size">\
								<option value="thumb">Thumbnail</option>\
								<option value="full">Full</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Title Brands</label></th>\
						<td>\
							<select fieldname="pw_show_title">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Count Of Brands</label></th>\
						<td>\
							<select fieldname="pw_show_count">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Tooltip</label></th>\
						<td>\
							<select fieldname="pw_tooltip">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Style</label></th>\
						<td>\
							<select fieldname="pw_style">\
								<option value="wb-thumb-style1">Style 1</option>\
								<option value="wb-thumb-style2">Style 2</option>\
								<option value="wb-thumb-style3">Style 3</option>\
								<option value="wb-thumb-style4">Style 4</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Desktop Columns</label></th>\
						<td>\
							<select fieldname="pw_columns">\
								<option value="wb-col-md-12">1</option>\
								<option value="wb-col-md-6">2</option>\
								<option value="wb-col-md-4">3</option>\
								<option value="wb-col-md-3">4</option>\
								<option value="wb-col-md-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Tablet Columns</label></th>\
						<td>\
							<select fieldname="pw_tablet_columns">\
								<option value="wb-col-sm-12">1</option>\
								<option value="wb-col-sm-6">2</option>\
								<option value="wb-col-sm-4">3</option>\
								<option value="wb-col-sm-3">4</option>\
								<option value="wb-col-sm-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Mobile Columns</label></th>\
						<td>\
							<select fieldname="pw_mobile_columns">\
								<option value="wb-col-xs-12">1</option>\
								<option value="wb-col-xs-6">2</option>\
								<option value="wb-col-xs-4">3</option>\
								<option value="wb-col-xs-3">4</option>\
								<option value="wb-col-xs-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Round Corner</label></th>\
						<td>\
							<select fieldname="pw_round_corner">\
								<option value="wb-car-no">No</option>\
								<option value="wb-thumb-round">Yes</option>\
							</select>\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end thumbnails-->\
<!-- Products By Brand`s Carousel -->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_product_carousel">\
				<h2>Select Brand For Display Product`s Carousel</h2>\
				<div class="flash_sale-shortcodes-form-fields">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Brands</label></th>\
						<td>\
							<select class="select_brand_single chosen-select_brand_n" fieldname="pw_brand" multiple="multiple" name="single" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s In Title</label></th>\
						<td>\
							<select fieldname="pw_show_title">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Brand`s Title Style</label></th>\
						<td>\
							<select fieldname="pw_title_style">\
								<option value="wb-brandpro-car-header-style1">Style 1</option>\
								<option value="wb-brandpro-car-header-style2">Style 2</option>\
								<option value="wb-brandpro-car-header-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr style="background-color: rgb(249, 249, 249);color: #000;">\
						<th><label>Carousel Setting`sCarousel Setting`s</label></th>\
						<td></td>\
					</tr>\
					<tr>\
						<th><label>Item Style</label></th>\
						<td>\
							<select fieldname="pw_item_style">\
								<option value="wb-brandpro-style1">Style 1</option>\
								<option value="wb-brandpro-style2">Style 2</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Controller/Pagination Style</label></th>\
						<td>\
							<select fieldname="pw_carousel_style">\
								<option value="wb-carousel-style1">Style 1</option>\
								<option value="wb-carousel-style2">Style 2</option>\
								<option value="wb-carousel-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Carousel Skin</label></th>\
						<td>\
							<select fieldname="pw_carousel_skin_style">\
								<option value="wb-carousel-skin1">Light</option>\
								<option value="wb-carousel-skin2">Dark</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Slide direction</label></th>\
						<td>\
							<select fieldname="pw_slide_direction">\
								<option value="vertical">Vertical</option>\
								<option value="horizontal">Horizontal</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Item Width</label></th>\
						<td><input type="number" value="" fieldname="pw_item_width" style="width: 30%;" /> px</td>\
					</tr>\
					<tr>\
						<th><label>Item Marrgin</label></th>\
						<td><input type="number" value="" fieldname="pw_item_marrgin" style="width: 30%;" /> px</td>\
					</tr>\
					<tr>\
						<th><label>Show Pagination</label></th>\
						<td>\
							<select fieldname="pw_show_pagination">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Control</label></th>\
						<td>\
							<select fieldname="pw_show_control">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Item Per View</label></th>\
						<td><input type="number" value="" fieldname="pw_item_per_view" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Item Per Slide</label></th>\
						<td><input type="number" value="" fieldname="pw_item_per_slide" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Slide Speed</label></th>\
						<td>\
							<select fieldname="pw_slide_speed">\
								<option value="1000">1 sec</option>\
								<option value="2000">2 sec</option>\
								<option value="3000">3 sec</option>\
								<option value="4000">4 sec</option>\
								<option value="5000">5 sec</option>\
								<option value="6000">6 sec</option>\
								<option value="7000">7 sec</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Auto play</label></th>\
						<td>\
							<select fieldname="pw_auto_play">\
								<option value="true">Yes</option>\
								<option value="false">No</option>\
							</select>\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end Products By Brand`s Carousel -->\
<!-- All Views -->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_all_views">\
				<h2>Add Shortcode List Views</h2>\
				<div class="flash_sale-shortcodes-form-fields" >\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Layout</label></th>\
						<td>\
							<select fieldname="type" class="pw_list_view_layout">\
								<option value="simple">Simple Layout</option>\
								<option value="adv1">Advanced Layout 1</option>\
								<option value="adv2">Advanced Layout 2(MultiSelect)</option>\
							</select>\
						</td>\
					</tr>\
					\
					<tr class="tr-adv1">\
						<th><label>Advanced Layout 1</label></th>\
						<td>\</td>\
					</tr>\
			<!-- Start Adv1 -->\
					<tr class="tr-adv1">\
						<th><label>Category For Fillter</label></th>\
						<td>\
							<select class="select_cat_single chosen-select_cat" fieldname="pw_adv1_category" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					</tr>\
					<tr class="tr-adv1">\
						<th><label>Orderby Brands</label></th>\
						<td>\
							<select fieldname="pw_adv1_order_by">\
								<option value="name">Name</option>\
								<option value="count">Count</option>\
							</select>\
						</td>\
					</tr>\
			<!-- End Adv1 -->\
			<!-- Start Adv2 -->\
					<tr class="tr-adv2">\
						<th><label>Advanced Layout 2</label></th>\
						<td>\</td>\
					</tr>\
					<tr class="tr-adv2">\
						<th><label>Category For Fillter</label></th>\
						<td>\
							<select class="select_cat_single chosen-select_cat" fieldname="pw_adv2_category" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr class="tr-adv2">\
					<tr class="tr-adv2">\
						<th><label>Filter style</label></th>\
						<td>\
							<select fieldname="pw_filter_style">\
								<option value="wb-multi-filter-style1">Style 1</option>\
								<option value="wb-multi-filter-style2">Style 2</option>\
								<option value="wb-multi-filter-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr class="tr-adv2">\
						<th><label>Orderby Brand</label></th>\
						<td>\
							<select fieldname="pw_adv2_order_by">\
								<option value="name">Name</option>\
								<option value="count">Count</option>\
							</select>\
						</td>\
					</tr>\
			<!-- End Adv2 -->\
			<!-- Start Simple-->\
					<tr>\
						<th><label>Display Only Featured Brands</label></th>\
						<td>\
							<select fieldname="pw_featured">\
								<option value="no">No</option>\
								<option value="yes">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Count of Products</label></th>\
						<td>\
							<select fieldname="pw_show_count">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Hide Empty Brands</label></th>\
						<td>\
							<select fieldname="pw_hide_empty_brands">\
								<option value="0">No</option>\
								<option value="1">Yes</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Image</label></th>\
						<td>\
							<select fieldname="pw_show_image">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Brand`s Title</label></th>\
						<td>\
							<select fieldname="pw_show_title">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Desktop Columns</label></th>\
						<td>\
							<select fieldname="pw_columns">\
								<option value="wb-col-md-12">1</option>\
								<option value="wb-col-md-6">2</option>\
								<option value="wb-col-md-4">3</option>\
								<option value="wb-col-md-3">4</option>\
								<option value="wb-col-md-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Tablet Columns</label></th>\
						<td>\
							<select fieldname="pw_tablet_columns">\
								<option value="wb-col-sm-12">1</option>\
								<option value="wb-col-sm-6">2</option>\
								<option value="wb-col-sm-4">3</option>\
								<option value="wb-col-sm-3">4</option>\
								<option value="wb-col-sm-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Mobile Columns</label></th>\
						<td>\
							<select fieldname="pw_mobile_columns">\
								<option value="wb-col-xs-12">1</option>\
								<option value="wb-col-xs-6">2</option>\
								<option value="wb-col-xs-4">3</option>\
								<option value="wb-col-xs-3">4</option>\
								<option value="wb-col-xs-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Style</label></th>\
						<td>\
							<select fieldname="pw_style">\
								<option value="wb-allview-style1">Style 1</option>\
								<option value="wb-allview-style2">Style 2</option>\
								<option value="wb-allview-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Tooltip</label></th>\
						<td>\
							<select fieldname="pw_tooltip">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
			<!-- End Simple -->\
				</table>\
				</div>\
			</div>\
<!-- end All Views -->\
<!-- products Grid-->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_product_grid">\
				<h2>Add products List By Brand`s</h2>\
				<div class="flash_sale-shortcodes-form-fields">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Brands</label></th>\
						<td>\
							<select class="select_brand_single chosen-select_brand_n" fieldname="pw_brand" multiple="multiple" name="single" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
						<th><label>Display Brands In Title </label></th>\
						<td>\
							<select fieldname="pw_show_title">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Brand`s Title Style</label></th>\
						<td>\
							<select fieldname="pw_title_style">\
								<option value="wb-brandpro-car-header-style1">Style 1</option>\
								<option value="wb-brandpro-car-header-style2">Style 2</option>\
								<option value="wb-brandpro-car-header-style3">Style 3</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Columns</label></th>\
						<td><input type="number" value="" fieldname="pw_columns" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Number Per Page</label></th>\
						<td><input type="number" value="" fieldname="pw_posts_per_page" style="width: 30%;" /></td>\
					</tr>\
					<tr>\
						<th><label>Order By</label></th>\
						<td>\
							<select fieldname="pw_orderby">\
								<option value="name">Name</option>\
								<option value="rand">Round</option>\
								<option value="author">Author</option>\
								<option value="title">Title</option>\
								<option value="date">Date</option>\
								<option value="ID">ID</option>\
								<option value="modified">Modified</option>\
								<option value="none">None</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Order</label></th>\
						<td>\
							<select fieldname="pw_order">\
								<option value="ASC">ASC</option>\
								<option value="DESC">DESC</option>\
							</select>\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end product grid-->\
<!--Brand Fillter Category-->\
			<div class="flash_sale-shortcodes-form-tab" id="flash_sale-shortcodes-form-tab_filter_brand">\
				<h2>Add Shortcode</h2>\
				<div class="flash_sale-shortcodes-form-fields">\
				<table cellpadding="0" cellspacing="0">\
					<tr>\
						<th><label>Category For Fillter</label></th>\
						<td>\
							<select class="select_cat_single chosen-select_cat" fieldname="pw_adv1_category" multiple="multiple" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Brands For Fillter</label></th>\
						<td>\
							<select class="select_brand_single chosen-select_brand_n" fieldname="pw_brand" multiple="multiple" name="single" placeholder="Choose Brand">\
								<option value="">Select Brand</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Display Product`s Image</label></th>\
						<td>\
							<select fieldname="pw_show_image">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Desktop Columns</label></th>\
						<td>\
							<select fieldname="pw_columns">\
								<option value="wb-col-md-12">1</option>\
								<option value="wb-col-md-6">2</option>\
								<option value="wb-col-md-4">3</option>\
								<option value="wb-col-md-3">4</option>\
								<option value="wb-col-md-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Tablet Columns</label></th>\
						<td>\
							<select fieldname="pw_tablet_columns">\
								<option value="wb-col-sm-12">1</option>\
								<option value="wb-col-sm-6">2</option>\
								<option value="wb-col-sm-4">3</option>\
								<option value="wb-col-sm-3">4</option>\
								<option value="wb-col-sm-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Mobile Columns</label></th>\
						<td>\
							<select fieldname="pw_mobile_columns">\
								<option value="wb-col-xs-12">1</option>\
								<option value="wb-col-xs-6">2</option>\
								<option value="wb-col-xs-4">3</option>\
								<option value="wb-col-xs-3">4</option>\
								<option value="wb-col-xs-2">6</option>\
							</select>\
						</td>\
					</tr>\
					<tr>\
						<th><label>Show Price</label></th>\
						<td>\
							<select fieldname="pw_show_price">\
								<option value="yes">Yes</option>\
								<option value="no">No</option>\
							</select>\
						</td>\
					</tr>\
				</table>\
				</div>\
			</div>\
<!-- end tab top_products Grid-->\
		</div></div>\
		<div class="flash_sale-shortcodes-submit">\
			<input style="display:none" id="flash_sale-shortcodes-form-type" value="button" />\
			<textarea style="display:none" id="flash_sale-shortcodes-form-code-to-add"></textarea>\
			<input type="button" id="flash_sale_shortcodes-submit" class="button-primary" value="Insert Shortcode" name="submit" />\
		</div>\
	</div></div>');
		
		form.appendTo('body').hide();


		jQuery.ajax ({
			type: "POST",
			url: ajaxurl,
			data:   "action=pw_fetch_woocommerce_brand",
			success: function(data) {
				//confirm(data);
				jQuery(".select_brand").html(data);
				jQuery('.chosen-select_brand').chosen();				
			}
		});
		jQuery.ajax ({
			type: "POST",
			url: ajaxurl,
			data:   "single=d&action=pw_fetch_woocommerce_brand",
			success: function(data) {
				//confirm(data);
				jQuery(".select_brand_single").html(data);
				jQuery('.chosen-select_brand_n').chosen();
			}
		});
		jQuery.ajax ({
			type: "POST",
			url: ajaxurl,
			data:   "single=d&action=pw_fetch_woocommerce_brand_category",
			success: function(data) {
				//confirm(data);
				jQuery(".select_cat_single").html(data);
				jQuery('.chosen-select_cat').chosen();
			}
		});		
		jQuery.ajax ({
			type: "POST",
			url: ajaxurl,
			data:   "action=pw_fetch_woocommerce_brand_category",
			success: function(data) {
				//confirm(data);
				jQuery(".select_brand_category").html(data);
				jQuery('.chosen-select_brand_cat').chosen();
			}
		});

		/* Change tab */
		//var flash_sale_shortcode_type = "list_rule";
		var flash_sale_shortcode_type = "a-z-view";
		var flash_sale_shortcode_code = "";
		form.find('.flash_sale-shortcodes-form-types ul li a').click(function(){
			flash_sale_shortcode_type = jQuery(this).parent('li').attr('type');
			jQuery('input#flash_sale-shortcodes-form-type').val(flash_sale_shortcode_type);
			jQuery('.flash_sale-shortcodes-form-tab').hide();
			jQuery('#flash_sale-shortcodes-form-tab_'+flash_sale_shortcode_type).show();
			jQuery('.flash_sale-shortcodes-form-types .active').removeClass('active');
			jQuery(this).parent('li').addClass('active');
			jQuery('.flash_sale-shortcodes-form .flash_sale-shortcodes-form-types').css({"height":jQuery('.flash_sale-shortcodes-form-tabs').outerHeight()});
			return false;
		});
		
		/* Choose icon */
		jQuery('.flash_sale-shortcodes-toggle-icon-list').click(function() {
			jQuery(this).parent('td').find('.flash_sale-shortcodes-icon-list-holder').fadeToggle();
			return false;
		});
		jQuery('.flash_sale-shortcodes-icon-list li a').click(function() {
			jQuery(this).parentsUntil('td').parent('td').find('input[type="text"]:first').val(jQuery(this).find('span').text());
			jQuery('.flash_sale-shortcodes-icon-list-holder').hide();
			jQuery(this).parentsUntil('td').parent('td').find('.current-icon').attr({'class':'current-icon fa-icon- fa-icon-'+jQuery(this).find('span').text()});
			
			var icon_to_add = jQuery(this).find('span').text();
			if(icon_to_add.search('sn-social-icon') > -1) {
				jQuery(this).parentsUntil('td').parent('td').find('.current-social-icon').attr({'class':'current-social-icon sn-social-icon- '+jQuery(this).find('span').text()});
				jQuery(this).parentsUntil('td').parent('td').find('.social-icon').hide();
				jQuery(this).parentsUntil('td').parent('td').find('.current-social-icon').show();
			} else {
				jQuery(this).parentsUntil('td').parent('td').find('.social-icon').html('<img src="../wp-content/plugins/flash_sale-shortcodes/images/social-icons/'+jQuery(this).find('span').text()+'.png" alt="" />');
				jQuery(this).parentsUntil('td').parent('td').find('.social-icon').show();
				jQuery(this).parentsUntil('td').parent('td').find('.current-social-icon').hide();
			}
			return false;
		});
	
		/* Colour Picker */
		if(jQuery('.brand-button_colour_custom').size() > 0) {
			jQuery('.brand-button_colour_custom').wpColorPicker();
		}
		
		/* Choose Column Structure */
		var num_of_columns = 2;
		jQuery('.column-structures a').click(function() {
			jQuery('.column-structures a').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.column-structures input').val(jQuery(this).attr('split'));
			num_of_columns = jQuery(this).attr('split');
			num_of_columns = num_of_columns.split('|');
			num_of_columns = num_of_columns.length;
			
			jQuery('#flash_sale-shortcodes-form-tab_columns textarea').attr({'disabled':'disabled'});
			var i = -1;
			while(i < (num_of_columns - 1)) {
				i++;
				jQuery('#flash_sale-shortcodes-form-tab_columns textarea').eq(i).removeAttr('disabled');
			}
			
			return false;
		});

		var num_of_columns = 2;
		jQuery('.column-structuresss a').click(function() {
			jQuery('.column-structuresss a').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.column-structuresss input').val(jQuery(this).attr('split'));
			num_of_columns = jQuery(this).attr('split');
			num_of_columns = num_of_columns.split('|');
			num_of_columns = num_of_columns.length;
			
			jQuery('#flash_sale-shortcodes-form-tab_columns textarea').attr({'disabled':'disabled'});
			var i = -1;
			while(i < (num_of_columns - 1)) {
				i++;
				jQuery('#flash_sale-shortcodes-form-tab_columns textarea').eq(i).removeAttr('disabled');
			}
			
			return false;
		});		
		var num_of_columns = 2;
		jQuery('.column-structuress a').click(function() {
			jQuery('.column-structuress a').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.column-structuress input').val(jQuery(this).attr('split'));
			num_of_columns = jQuery(this).attr('split');
			num_of_columns = num_of_columns.split('|');
			num_of_columns = num_of_columns.length;
			
			jQuery('#flash_sale-shortcodes-form-tab_columns textarea').attr({'disabled':'disabled'});
			var i = -1;
			while(i < (num_of_columns - 1)) {
				i++;
				jQuery('#flash_sale-shortcodes-form-tab_columns textarea').eq(i).removeAttr('disabled');
			}
			
			return false;
		});
		/* On submit click */
	
		form.find('#flash_sale_shortcodes-submit').click(function(){
			/* Create shortcode */
			flash_sale_shortcode_code = '';

			if(flash_sale_shortcode_type == "product_rulea") {
				
				/* Social shortcode */
				flash_sale_shortcode_code = flash_sale_shortcode_code + '[pw_brand_' + flash_sale_shortcode_type + ']';
				
					jQuery('#flash_sale-shortcodes-form-tab_' + flash_sale_shortcode_type + ' td').each(function() {
						if(jQuery(this).find('input:first').val() == "" || jQuery(this).find('input:first').val() == undefined) { } else {
						
							flash_sale_shortcode_code = flash_sale_shortcode_code + '[flash_sale_social_link service="';
							flash_sale_shortcode_code = flash_sale_shortcode_code + jQuery(this).find('input').eq(0).val();
							flash_sale_shortcode_code = flash_sale_shortcode_code + '" link="';
							flash_sale_shortcode_code = flash_sale_shortcode_code + jQuery(this).find('input').eq(1).val();
							flash_sale_shortcode_code = flash_sale_shortcode_code + '"] ';
							
						}
					});
				
			//	flash_sale_shortcode_code = flash_sale_shortcode_code + '[/flash_sale_' + flash_sale_shortcode_type + ']';
			
			}else {
				
				/* Basic shortcodes */
				flash_sale_shortcode_code = flash_sale_shortcode_code + '[pw_brand_' + flash_sale_shortcode_type + ' ';
				
				jQuery('#flash_sale-shortcodes-form-tab_' + flash_sale_shortcode_type + ' input, #flash_sale-shortcodes-form-tab_' + flash_sale_shortcode_type + ' select, #flash_sale-shortcodes-form-tab_' + flash_sale_shortcode_type + ' textarea').each(function() {
					
					if(jQuery(this).attr('fieldname') != "" && jQuery(this).attr('fieldname') != undefined) {
						flash_sale_shortcode_code = flash_sale_shortcode_code + ' ' + jQuery(this).attr('fieldname') + '="' + jQuery(this).val() + '"';
					}
					
				});
				flash_sale_shortcode_code = flash_sale_shortcode_code + ']';
			}
				
			/* Insert shortcode */
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, flash_sale_shortcode_code);
			tb_remove();
			
			return false;
			
		});
	});
	jQuery('.tr-adv1').dependsOn({
		'.pw_list_view_layout': {
			values: ['adv1']
		}
	});
	jQuery('.tr-adv2').dependsOn({
		'.pw_list_view_layout': {
			values: ['adv2']
		}
	});
	
	jQuery('.tr_simple').dependsOn({
		'.pw_list_view_layout': {
			values: ['simple']
		}
	});		
	
})()