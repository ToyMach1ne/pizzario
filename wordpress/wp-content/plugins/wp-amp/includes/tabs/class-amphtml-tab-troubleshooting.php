<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AMPHTML_Tab_Troubleshooting extends AMPHTML_Tab_Abstract {

	public function get_fields() {
		return array (
			'troubleshooting' => array (
				'id'               => 'troubleshooting',
				'title'            => __( 'Troubleshooting', 'amphtml' ),
				'display_callback' => array ( $this, '' )
			)
		);
	}

	public function include_troubleshooting_tab() {
		require_once( ( dirname( __FILE__ ) . '/tab-troubleshooting.php' ) );
	}

	public function get_section_callback( $id ) {
		return array ( $this, 'include_troubleshooting_tab' );
	}

	public function get_submit() {
		return null;
	}

}