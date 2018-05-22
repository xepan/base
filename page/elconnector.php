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


class page_elconnector extends \Page {
	public $title='Page Title';

	function init(){
		parent::init();

		if(!$this->app->auth->isLoggedIn()) return;

		$path_asset = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/assets';
		$path_www = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/www';
		
		\elFinder::$netDrivers['ftp'] = 'FTP';
		\elFinder::$netDrivers['dropbox'] = 'Dropbox';

		$roots=array(
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_www,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/www',
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
		            'path'   => $path_asset,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/assets',
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        )
		    );

		if($_GET['www_root']){
			$roots=array(
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path_www,
		            'URL'    => 'websites/'.$this->app->current_website_name.'/www',
		            'plugin' => array(
		                'Sanitizer' => array(
		                    'enable' => true,
		                    'targets'  => array('\\','/',':','*','?','"','<','>','|',' '), // target chars
		                    'replace'  => '_'    // replace to this
		                )
		            )
		        )
		    );
		}
		
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
		    'roots'  => $roots
		);

		// run elFinder
		$connector = new \elFinderConnector(new \elFinder($opts));
		$connector->run();
		exit;
	}
}