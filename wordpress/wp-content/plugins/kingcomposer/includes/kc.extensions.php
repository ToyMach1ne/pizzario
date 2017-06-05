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

/*
*	Extensions class
*/

class kc_extensions {
	
	private $tab = 'installed';
	private $page = 1;
	private $path = '';
	private $errors = array();
	
	function __construct(){
		
		$this->path = untrailingslashit(ABSPATH).KDS.'wp-content'.KDS.'kc-extensions'.KDS;
		
		if (is_admin()) {
			
			add_action ('admin_menu', array( &$this, 'admin_menu' ), 1);
			if (isset($_GET['tab']) && !empty($_GET['tab']))
				$this->tab = $_GET['tab'];
			if (isset($_GET['page']) && !empty($_GET['page']))
				$this->page = $_GET['page'];
				
			add_action('kc_list_extensions_store', array(&$this, 'extensions_store'));
			add_action('kc_list_extensions_installed', array(&$this, 'extensions_installed'));
			
		}
		
		$this->load_extensions();
		
	}
	
	public function admin_menu() {
		
		$capability = apply_filters('access_kingcomposer_capability', 'access_kingcomposer');
		add_submenu_page(
			'kingcomposer',
			__('Extensions', 'kingcomposer'), 
			__('Extensions', 'kingcomposer'),
			$capability,
			'kc-extensions',
			array( &$this, 'screen_display' )
		);
		
	}
	
	public function screen_display() {
		
		include 'extensions/kc.screen.tmpl.php';
		
	}
	
	public function extensions_store($page = 1) {
		
		$items = array(
			array(
				'name' => 'Extension',
				'thumbnail' => 'http://ps.w.org/buddypress/assets/icon.svg?rev=1534012',
				'description' => 'This is descrition of a KC Extension, It containers about 250 words and do not allow special characters',
				'author_link' => '#',
				'author' => 'KC Team',
				'last_updated' => '5 days ago',
				'downloads' => '123',
				'version' => '1.1',
				'price' => '$4.99',
				'preview' => '#'
			)
		);
		
		include 'extensions/kc.store.tmpl.php';

	}
		
	public function extensions_installed ($page = 1) {
		
		$items = $this->load_installed('all');
		$actives = (array) get_option( 'kc_active_extensions', array() );
		include 'extensions/kc.installed.tmpl.php';
		
	}
	
	public function load_installed ($mod = 'all') {
		
		$items = array();
		$files = scandir($this->path, 0);
		
		foreach ($files as $file) {
			
			if (is_dir($this->path.$file) && $file != '.' && $file != '..') {
				
				if (file_exists($this->path.$file.KDS.'index.php')) {
					
					$data = get_file_data($this->path.$file.KDS.'index.php', array(
						'Extension Name',
						'Extension Preview',
						'Description',
						'Version',
						'Author',
						'Author URI',
					));
					
					$items[] = array(
						'Extension Name' => !empty($data[0]) ? $data[0] : 'Unknow',
						'Extension Preview' => !empty($data[1]) ? $data[1] : '',
						'Description' => !empty($data[2]) ? $data[2] : '',
						'Version' => !empty($data[3]) ? $data[3] : '1.0',
						'Author' => !empty($data[4]) ? $data[4] : 'Unknow',
						'Author URI' => !empty($data[5]) ? $data[5] : '#unknow',
						'extension' => sanitize_title($file)
					);
				}
			}
		}
		
		return $items;
		
	}
	
	public function load_extensions () {
		
		$actives = (array) get_option( 'kc_active_extensions', array() );
		foreach ($actives as $name => $stt) {
			if ($stt == 1) {
				if (file_exists($this->path.$name.KDS.'index.php')) {
					require_once($this->path.$name.KDS.'index.php');
					$ex_class = 'kc_extension_'.str_replace('-', '_', sanitize_title($name));
					if (class_exists($ex_class)) {
						new $ex_class();
					} else {
						$this->errors[] = 'Could not find the PHP classname "'.$ex_class.'" in the extenstion "/'.$name.KDS.'index.php"';
						unset($actives[$name]);
						update_option('kc_active_extensions', $actives);
					} 
				} else {
					$this->errors[] = 'Could not find the extension file /'.$name.KDS.'index.php';
					unset($actives[$name]);
					update_option('kc_active_extensions', $actives);
				}
			}
		}
		
		return $this->errors;
		
	}
	
}

class kc_extension {
	
	public $path;
	public $url;
	
	public function init($file) {
		
		$this->path = dirname($file);
		$this->url = site_url('/wp-content/kc-extensions/'.basename(dirname($file)));
		
	}
	
	public function map($args) {
		
		global $kc;
		if (empty($args) || !is_array($args))
			return;
		
		$kc->add_map($args);
		
	}
	
	public function output($name, $callback) {
		if (is_callable($callback)) {
			add_shortcode ($name, $callback);
		}
	}
		
}

new kc_extensions();
