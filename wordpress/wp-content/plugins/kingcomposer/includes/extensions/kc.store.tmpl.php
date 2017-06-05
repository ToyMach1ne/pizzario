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

<?php
if (count($items) > 0) {
	foreach ($items as $item) {
?>
	<div class="plugin-card plugin-card-buddypress">
		<div class="plugin-card-top">
			<div class="name column-name">
				<h3>
					<a href="#" class="thickbox open-plugin-details-modal">
						<?php echo esc_html($item['name']); ?>
						<img src="<?php echo esc_url($item['thumbnail']); ?>" class="plugin-icon" alt="">
					</a>
				</h3>
			</div>
			<div class="action-links">
				<ul class="plugin-action-buttons">
					<li>
						<price style="color: green"><?php echo esc_html($item['price']); ?></price>
						<a class="install-now button"href="#">
							<?php _e('Install Now', 'kingcomposer'); ?>
						</a>
					</li>
					<?php if (isset($item['preview']) && !empty($item['preview'])) { ?>
					<li>
						<a href="<?php echo esc_url($item['preview']); ?>"><?php _e('Live Preview', 'kingcomposer'); ?></a>
					</li>
					<?php } ?>
				</ul>				
			</div>
			<div class="desc column-description">
				<p><?php echo $item['description']; ?></p>
				<p class="authors">
					<cite>
						<?php _e('By', 'kingcomposer'); ?> 
						<a href="<?php echo esc_url($item['author_link']); ?>">
							<?php echo esc_html($item['author']); ?>
						</a>
					</cite>
				</p>
			</div>
		</div>
		<div class="plugin-card-bottom">
			<div class="column-updated">
				<strong>
					<?php _e('Last Updated', 'kingcomposer'); ?>:
				</strong> 
				<?php echo esc_html($item['last_updated']); ?>		
			</div>
			<div class="column-downloaded">
				<?php echo $item['downloads']; ?> <?php _e('downloads', 'kingcomposer'); ?> | 
				<?php _e('Ver', 'kingcomposer'); ?> <?php echo esc_html($item['version']); ?> 
			</div>
		</div>
	</div>
<?php 
	}
}
?>