<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Status extends AMPHTML_Tab_Abstract {

	public function get_fields() {
		return array(
			'troubleshooting' => array(
				'id'               => 'system_status',
				'title'            => __( 'System Status', 'amphtml' ),
				'display_callback' => array( $this, '' )
			)
		);
	}

	public function get_system_info() {
		global $wpdb;

		return array(
			'server_info'        => esc_html( $_SERVER['SERVER_SOFTWARE'] ),
			'php_version'        => PHP_VERSION,
			'abspath'            => ABSPATH,
			'php_display_errors' => ini_get( 'display_errors' ),
			'suhosin_installed'  => extension_loaded( 'suhosin' ),
			'mysql_ver'          => $wpdb->db_version(),
			'fsockopen_curl'     => ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) ? true : false,
			'allow_url_fopen'    => ini_get( 'allow_url_fopen' ) ? true : false,
			'wp_version'         => get_bloginfo( 'version', 'display' ),
			'wc_version'         => function_exists( 'WC' ) ? WC()->version : ''
		);
	}

	public function get_requirement( $name ) {
		$requirements = array(
			'php_version' => '5.4.0',
			'wc_version'  => '2.5',
			'wp_version'  => '4.5',
		);

		return isset( $requirements[ $name ] ) ? $requirements[ $name ] : false;
	}

	protected function get_latest_wp_version() {
		$response = wp_remote_get( 'https://api.wordpress.org/core/version-check/1.7/' );
		$obj      = json_decode( $response['body'] );

		return $obj->offers[0]->version;
	}

	public function display_system_info() {

		$sysinfo = $this->get_system_info();
		?>

		<table class="widefat striped" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3"
				    data-export-label="Server Environment"><?php _e( 'Server Environment', 'amphtml' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Server Info"><?php _e( 'Server Info', 'amphtml' ); ?>:</td>
				<td><?php echo $sysinfo['server_info']; ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Version"><?php _e( 'PHP Version', 'amphtml' ); ?>:</td>
				<td><?php
					if ( version_compare( $sysinfo['php_version'], $this->get_requirement('php_version') ) >=0 ) {
						echo $sysinfo['php_version'];
					} else {
						printf( "version <strong style='color:red;'>%s</strong> is out of date", $sysinfo['php_version'] );
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="WP Version"><?php _e( 'WP Version', 'amphtml' ); ?>:</td>
				<td><?php
					if ( $sysinfo['wp_version'] < $this->get_requirement('wp_version') ) {
						printf( "version <strong style='color:red;'>%s</strong> is out of date", $sysinfo['wp_version'] );
					} else {
						echo $sysinfo['wp_version'];
					}
					?></td>
			</tr>
			<?php if( $sysinfo['wc_version'] ): ?>
				<tr>
					<td data-export-label="WooCommerce Version"><?php _e( 'WooCommerce Version', 'amphtml' ); ?>:</td>
					<td><?php
						if ( $sysinfo['wc_version'] < $this->get_requirement('wc_version') ) {
							printf( "<mark class='error'>version <strong style='color:red;'>%s</strong> is out of date</mark>", $sysinfo['wc_version'] );
						} else {
							echo  $sysinfo['wc_version'];
						}
						?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td data-export-label="ABSPATH"><?php _e( 'ABSPATH', 'amphtml' ); ?>:</td>
				<td><?php echo '<code>' . $sysinfo['abspath'] . '</code>'; ?></td>
			</tr>
			<tr>
				<td data-export-label="MySQL Version"><?php _e( 'MySQL Version', 'amphtml' ); ?>:</td>
				<td><?php echo $sysinfo['mysql_ver']; ?></td>
			</tr>
			<tr>
				<td data-export-label="allow_url_fopen"><?php _e( 'allow_url_fopen', 'amphtml' ); ?>:</td>
				<td><?php if ( $sysinfo['allow_url_fopen'] == true ) {
						echo '<mark class="yes">' . '&#10004;' . '</mark>';
					} else {
						echo '<mark class="no">' . '&ndash;' . '</mark>';
					} ?></td>
			</tr>
			<tr>
				<td data-export-label="cURL Version"><?php _e( 'cURL Version', 'amphtml' ); ?>:</td>
				<td><?php
					if ( function_exists( 'curl_version' ) ) {
						$curl_version = curl_version();
						echo $curl_version['version'] . ', ' . $curl_version['ssl_version'];
					} else {
						_e( 'N/A', 'amphtml' );
					}
					?></td>
			</tr>
			<?php
			$posting = array();

			// fsockopen/cURL
			$posting['fsockopen_curl']['name'] = 'fsockopen/cURL';

			if ( $sysinfo['fsockopen_curl'] === true ) {
				$posting['fsockopen_curl']['success'] = true;
			} else {
				$posting['fsockopen_curl']['success'] = false;
			}

			foreach ( $posting as $post ) {
				$mark = ! empty( $post['success'] ) ? 'yes' : 'error';
				?>
				<tr>
					<td data-export-label="<?php echo esc_html( $post['name'] ); ?>"><?php echo esc_html( $post['name'] ); ?>
						:
					</td>
					<td class="help">
						<mark class="<?php echo $mark; ?>">
							<?php echo ! empty( $post['success'] ) ? '&#10004' : '&#10005'; ?>
							<?php echo ! empty( $post['note'] ) ? wp_kses_data( $post['note'] ) : ''; ?>
						</mark>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<p><?php _e( 'See WordPress minimum requirements', 'amphtml' ) ?> <a href="https://wordpress.org/about/requirements/" target="_blank"><?php _e( 'here', 'amphtml' ) ?></a>.</p>
	<?php }

	public function get_section_callback( $id ) {
		return array( $this, 'display_system_info' );
	}

	public function get_submit() {
		return null;
	}

}