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

		$path = $this->app->pathfinder->base_location->base_path.'/websites/'.$this->app->current_website_name.'/assets';

		$opts = array(
		    'locale' => '',
		    'roots'  => array(
		        array(
		            'driver' => 'LocalFileSystem',
		            'path'   => $path,
		            'URL'    => 'http://localhost/xepan2/websites/'.$this->app->current_website_name.'/assets'
		        )
		    )
		);

		// run elFinder
		$connector = new \elFinderConnector(new \elFinder($opts));
		$connector->run();
		exit;
	}
}
