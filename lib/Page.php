<?php


namespace xepan\base;

class Page extends \Page {
	
	public $breadcrumb=[
						'Dashboard'=>'/'
					];

	function init(){
		parent::init();		

		$breadcrumbs = [];
		foreach ($this->breadcrumb as $title => $url) {
			$active='';
			if($url=='#'){
				$active='active';
			}
			else{
				if(is_array($url)){
					$_url = $url[0];
					unset($url[0]);
					$_params=$url;
					$url = $this->app->url($url,$_params);
				}else{
					$url = $this->app->url($url);
				}
			}

			$breadcrumbs[] = ['title'=>$title,'url'=>$url,'active'=>$active];
		}

		$br = $this->app->layout->add('CompleteLister',null,'breadcrumb',['layout/cube','breadcrumb']);
		$br->setSource($breadcrumbs);
		
	}
}