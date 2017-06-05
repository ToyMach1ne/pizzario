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
<?php if (count($this->errors) > 0) { ?>
<div id="message" class="error">
	<p><?php _e('There were some errors with the extensions are activated', 'kingcomposer'); ?>:</p>
	<ol>
		<?php
			foreach ($this->errors as $error) {
				echo '<li>'.$error.'</li>';
			}
		?>
	</ol>
</div>
<?php } ?>
<div class="tablenav top" id="kc-installed-extensions">
    <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
        <select name="action" id="bulk-action-selector-top">
			<option value="-1">Bulk Actions</option>
			<option value="activate-selected">Activate</option>
			<option value="deactivate-selected">Deactivate</option>
			<option value="update-selected">Update</option>
			<option value="delete-selected">Delete</option>
		</select>
        <input type="submit" id="doaction" class="button action float-left" value="Apply">
        <!--ul class="subsubsub">
			<li class="all"><a href="plugins.php?plugin_status=all" class="current">All <span class="count">(6)</span></a> |</li>
			<li class="active"><a href="plugins.php?plugin_status=active">Active <span class="count">(3)</span></a> |</li>
			<li class="inactive"><a href="plugins.php?plugin_status=inactive">Inactive <span class="count">(3)</span></a> |</li>
			<li class="upgrade"><a href="plugins.php?plugin_status=upgrade">Update Available <span class="count">(1)</span></a></li>
		</ul-->
    </div>
    <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($items); ?> items</span>
        <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
        <span class="paging-input">
        	<label for="current-page-selector" class="screen-reader-text">Current Page</label>
			<input class="current-page" id="current-page-selector" type="text" name="paged" value="1" size="1" aria-describedby="table-paging">
			<span class="tablenav-paging-text"> of <span class="total-pages">1</span>
		</span>
        </span>
        <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">»</span></span>
    </div>
    <br class="clear">
</div>

<table class="wp-list-table widefat plugins" id="kc-extensions-list">
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column">
	            <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All', 'kingcomposer'); ?></label>
	            <input id="cb-select-all-1" type="checkbox">
	        </td>
            <th scope="col" id="name" class="manage-column column-name column-primary"><?php _e('Extensions', 'kingcomposer'); ?></th>
            <th scope="col" id="description" class="manage-column column-description"><?php _e('Description', 'kingcomposer'); ?></th>
        </tr>
    </thead>

    <tbody id="the-list">
	    <tr class="no-extension">
            <td colspan="3">
	             <span style="font-size: 50px;">\(^Д^)/</span>
	            <br />
	            <br />
	            <p>
		            <h3><?php _e('Oops, There are no extension found', 'kingcomposer'); ?></h3>
		            <a href="admin.php?page=kc-extensions&tab=store" class="button button-primary button-large">
			            <?php _e('Add New Extension', 'kingcomposer'); ?>
			        </a>
	            </p>
	        </td>
        </tr>
	    <?php
		    
		if (count($items) > 0) {
			
			foreach ($items as $item) {
				
				$idc = rand(334,4343);
				$name = esc_html($item['Extension Name']);
				$slug = esc_attr($item['extension']);
	    ?>
        <tr class="<?php 
	        	
	        	if (isset($actives[$slug]) && $actives[$slug] == '1')
		        	echo 'active';
		        else echo 'inactive';
		        
		    ?>" data-extension="<?php echo $slug; ?>">
            <th scope="row" class="check-column">
	            <label class="screen-reader-text" for="checkbox_<?php echo $idc; ?>">
	            	Select <?php echo $name; ?>
	            </label>
	            <input type="checkbox" name="checked[]" value="iphotor/iphotor.php" id="checkbox_<?php echo $idc; ?>">
	        </th>
            <td class="plugin-title column-primary">
	            <strong><?php echo $name; ?></strong>
                <div class="row-actions visible">
	                <span class="activate">
	                	<a href="#active" class="active" aria-label="Activate <?php echo $name; ?>">
		                	<?php _e('Activate', 'kingcomposer'); ?>
		                </a> | 
	                </span>
	                <span class="deactivate">
	                	<a href="#deactive" class="deactive" aria-label="Activate <?php echo $name; ?>">
		                	<?php _e('Deactivate', 'kingcomposer'); ?>
		                </a> | 
	                </span>
	                <span class="delete">
	                	<a href="#delete" class="delete" aria-label="Delete <?php echo $name; ?>">
		                	<?php _e('Delete', 'kingcomposer'); ?>
		                </a>
	                </span>
	            </div>
            </td>
            <td class="column-description desc">
                <div class="plugin-description">
                    <p><?php echo esc_html($item['Description']); ?></p>
                </div>
                <div class="inactive second plugin-version-author-uri">
	                <?php _e('Version', 'kingcomposer'); ?> <?php echo esc_html($item['Version']); ?> | 
	                <?php _e('By', 'kingcomposer'); ?> 
	                <a href="<?php echo esc_url($item['Author URI']); ?>" target=_blank>
		                <?php echo esc_html($item['Author']); ?>
		            </a> 
	                <?php if (!empty($item['Extension Preview'])) { ?>
	                | 
	                <a href="<?php echo esc_url($item['Extension Preview']); ?>" target=_blank>
		                <?php _e('Preview', 'kingcomposer'); ?>
		            </a>
		            <?php } ?>
		        </div>
            </td>
        </tr>
        <?php 
	        
	       	} 
		}
	        
        ?>
    </tbody>

    <tfoot>
        <tr>
            <td class="manage-column column-cb check-column">
	            <label class="screen-reader-text" for="cb-select-all-2"><?php _e('Select All', 'kingcomposer'); ?></label>
	            <input id="cb-select-all-2" type="checkbox">
	        </td>
            <th scope="col" class="manage-column column-name column-primary"><?php _e('Extensions', 'kingcomposer'); ?></th>
            <th scope="col" class="manage-column column-description"><?php _e('Description', 'kingcomposer'); ?></th>
        </tr>
    </tfoot>

</table>

<div class="tablenav bottom">
    <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
        <select name="action2" id="bulk-action-selector-bottom">
			<option value="-1">Bulk Actions</option>
			<option value="activate-selected">Activate</option>
			<option value="deactivate-selected">Deactivate</option>
			<option value="update-selected">Update</option>
			<option value="delete-selected">Delete</option>
		</select>
        <input type="submit" id="doaction2" class="button action" value="Apply">
    </div>
    <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($items); ?> items</span>
        <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
        <span class="screen-reader-text">Current Page</span>
        <span id="table-paging" class="paging-input"><span class="tablenav-paging-text">1 of <span class="total-pages">1</span></span>
        </span>
        <span class="tablenav-pages-navspan" aria-hidden="true">›</span>
        <span class="tablenav-pages-navspan" aria-hidden="true">»</span></span>
    </div>
    <br class="clear">
</div>