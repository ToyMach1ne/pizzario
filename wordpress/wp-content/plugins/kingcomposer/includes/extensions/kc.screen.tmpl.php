<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.extension.php
*
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
?>
<div class="wrap">
	<h1><?php _e('KC Extensions', 'kingcomposer'); ?></h1>
	<div class="wp-filter">
		<ul class="filter-links">
			<li class="kc-extension-installed">
				<a href="admin.php?page=kc-extensions&tab=installed" class="<?php 
					if (empty($this->tab) || $this->tab == 'installed')
						echo 'current'; 
				?>">
					<?php _e('Installed', 'kingcomposer'); ?>
				</a>
			</li>
			<li class="kc-extension-store">
				<a href="admin.php?page=kc-extensions&tab=store" class="<?php 
					if ($this->tab == 'store')
						echo 'current'; 
				?>">
					<?php _e('Add New', 'kingcomposer'); ?>
				</a> 
			</li>
			<li class="kc-extension-updates">
				<a href="admin.php?page=kc-extensions&tab=updates" class="<?php 
					if ($this->tab == 'updates')
						echo 'current'; 
				?>">
					<?php _e('Updates', 'kingcomposer'); ?>
				</a>
			</li>
		</ul>
		<form class="search-form search-extensions" method="get">
			<input type="hidden" name="tab" value="search">
			<label class="screen-reader-text" for="typeselector"><?php _e('Search plugins by', 'kingcomposer'); ?>:</label>
			<label>
				<span class="screen-reader-text"><?php _e('Search Extensions', 'kingcomposer'); ?></span>
				<input type="search" name="s" value="" class="wp-filter-search" placeholder="<?php _e('Search Extensions', 'kingcomposer'); ?>" aria-describedby="live-search-desc">
			</label>
			<input type="submit" id="search-submit" class="button hide-if-js" value="<?php _e('Search Extensions', 'kingcomposer'); ?>">	
		</form>
	</div>
	<form id="extensions-filter" method="post">
		<div class="wp-list-table widefat">
			<?php do_action('kc_list_extensions_'.$this->tab, $this->page); ?>
		</div>
		<input type="hidden" name="kc-nonce" id="kc-nonce" value="<?php echo wp_create_nonce('kc-nonce'); ?>" />
	</form>
</div>
<script type="text/javascript" src="<?php echo esc_url(KC_URL); ?>/assets/js/kc.settings.js"></script>