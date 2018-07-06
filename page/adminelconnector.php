<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;


class page_adminelconnector extends \xepan\base\Page {
	public $title='Page Title';

	function init(){
		parent::init();

		$path_asset = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/assets';
		$path_www = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/www';
		$path_upload = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/upload';
		$path_backup = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/backup';
		
		\elFinder::$netDrivers['ftp'] = 'FTP';
		\elFinder::$netDrivers['dropbox'] = 'Dropbox';
		
		$opts = array(
			'bind' => array(
	 			'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre ls.pre' => array(
	 				'Plugin.Sanitizer.cmdPreprocess'
	 			),
	 			'ls' => array(
	 				'Plugin.Sanitizer.cmdPostprocess'
	 			),
	 			'upload.presave' => array(
	 				'Plugin.Sanitizer.onUpLoadPreSave'
	 			)
	 		),
		    'locale' => '',
		    'roots'  => array(
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_asset,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/assets',
		            'uploadMaxSize'=>'20M',
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        ),
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_www,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/www',
		            'uploadMaxSize'=>'20M',
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        ),
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_upload,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/upload',
		            'uploadMaxSize'=>'20M',
		            'attributes' => array(
						array(
							'pattern' => '/.*/', //You can also set permissions for file types by adding, for example, .jpg inside pattern.
							'read'    => true,
							'write'   => false,
							'locked'  => true
						)
					),
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        ),
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_backup,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/backup',
		            'uploadMaxSize'=>'20M',
		            'attributes' => array(
						array(
							'pattern' => '/.*/', //You can also set permissions for file types by adding, for example, .jpg inside pattern.
							'read'    => true,
							'write'   => false,
							'locked'  => true
						)
					),
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        )
		    )
		);

		// run elFinder
		$connector = new \elFinderConnector(new \elFinder($opts));
		$connector->run();
		exit;
	}
}
