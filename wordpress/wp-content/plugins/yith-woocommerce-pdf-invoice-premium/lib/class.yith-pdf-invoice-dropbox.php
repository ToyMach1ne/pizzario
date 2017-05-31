<?php

/**
 * Created by PhpStorm.
 * User: giuffrida
 * Date: 11/08/16
 * Time: 15.30
 */
class YITH_PDF_Invoice_DropBox {
	/**
	 * @var array the DropBox app information
	 */
	private $dropbox_app_info = array(
		'key'    => '58dmyrhs688d3zs',
		'secret' => 'er8q2p42m2tu7mz',
	);

	private $dropbox_accesstoken = '';

	/**
	 * Single instance of the class
	 *
	 * @since 1.0.0
	 */
	protected static $instance;

	/**
	 * Returns single instance of the class
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

		$this->dropbox_accesstoken = ywpi_get_option( 'ywpi_dropbox_access_token' );

		# Include the Dropbox SDK libraries
		if ( ! function_exists( 'Dropbox\autoload' ) ) {
			require_once YITH_YWPI_LIB_DIR . 'Dropbox/autoload.php';
		}
	}

	/**
	 * Save DropBox access token
	 */
	public function custom_save_ywpi_dropbox() {
		if ( isset( $_POST['ywpi_dropbox_key'] ) ) {
			//  Extract access token  if authorization token is valid
			$access_token = $this->get_dropbox_access_token( $_POST['ywpi_dropbox_key'] );
			if ( $access_token ) {
				update_option( 'ywpi_dropbox_access_token', $access_token );
			}
		}

		return null;
	}

	/**
	 * Disable the DropBox backup
	 *
	 * @return string
	 */
	public function disable_dropbox_backup() {
		if ( $this->dropbox_accesstoken ) {
			try {
				delete_option( 'ywpi_dropbox_access_token' );

				$dbxClient = new Dropbox\Client( $this->dropbox_accesstoken, "PHP-Example/1.0" );

				//  try to retrieve information to verify if access token is valid
				return $dbxClient->disableAccessToken();

			} catch ( \Dropbox\Exception $e ) {
				error_log( __( 'Dropbox backup: unable to disable authorization > ', 'yith-woocommerce-pdf-invoice' ) . $e->getMessage() );
			}
		}
	}

	/**
	 * Check if current access token is valid and retrieve account information
	 *
	 * @return array|bool
	 */
	public function get_dropbox_account_info() {
		if ( $this->dropbox_accesstoken ) {
			try {
				$dbxClient = new Dropbox\Client( $this->dropbox_accesstoken, "PHP-Example/1.0" );

				//  try to retrieve information to verify if access token is valid
				return $dbxClient->getAccountInfo();
			} catch ( \Dropbox\Exception $e ) {
				error_log( __( 'Dropbox backup: unable to retrieve account information > ', 'yith-woocommerce-pdf-invoice' ) . $e->getMessage() );

			}
		}

		return false;
	}

	/**
	 * Retrieve access token starting from an authorization code
	 *
	 * @param string $auth_code authorization code
	 *
	 * @return string
	 */
	private function get_dropbox_access_token( $auth_code ) {
		try {

			$appInfo = Dropbox\AppInfo::loadFromJson( $this->dropbox_app_info );
			$webAuth = new Dropbox\WebAuthNoRedirect( $appInfo, "PHP-Example/1.0" );

			list( $accessToken, $dropboxUserId ) = $webAuth->finish( $auth_code );

			return $accessToken;
		} catch ( Exception $e ) {
			error_log( __( 'Dropbox backup: unable to get access token > ', 'yith-woocommerce-pdf-invoice' ) . $e->getMessage() );
		}

		return false;
	}

	/**
	 * Upload document to dropbox, if access token is valid
	 *
	 * @param YITH_Document $document the document to upload
	 */
	public function send_document_to_dropbox( $document ) {

		if ( ! $this->dropbox_accesstoken ) {
			return;
		}

		try {
			$dbxClient = new Dropbox\Client( $this->dropbox_accesstoken, "PHP-Example/1.0" );

			if ( file_exists( $document->get_full_path() ) ) {
				$f = fopen( $document->get_full_path(), "rb" );
				$dbxClient->createFolder( '/' . $document->save_folder );
				$result = $dbxClient->uploadFile( '/' . $document->save_folder . '/' . $document->save_path, Dropbox\WriteMode::force(), $f );
				fclose( $f );
			}
		} catch ( Exception $e ) {
			error_log( __( 'Dropbox backup: unable to send file > ', 'yith-woocommerce-pdf-invoice' ) . $e->getMessage() );
		}
	}

	/**
	 * Get the url to the dropbox authentication url
	 *
	 * @return string
	 */
	public function get_dropbox_authentication_url() {

		$appInfo = Dropbox\AppInfo::loadFromJson( $this->dropbox_app_info );
		$webAuth = new Dropbox\WebAuthNoRedirect( $appInfo, "PHP-Example/1.0" );

		$authorizeUrl = $webAuth->start();

		return $authorizeUrl;
	}

}