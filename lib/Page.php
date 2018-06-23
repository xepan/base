<?php


namespace xepan\base;

class Page extends \Page {
	
	public $breadcrumb=[
						'Dashboard'=>'/'
					];
	public $allow_frontend = false;
	function init(){
		parent::init();

		$allowed_scope = ['AdminUser','SuperUser'];
		if($this->allow_frontend)
			$allowed_scope[] = 'WebsiteUser';
		
		if(!$this->app->auth->isLoggedIn() || !in_array($this->app->auth->model['scope'] , $allowed_scope)){
			throw $this->exception('You are not authorised to access this page')
						->addMoreInfo('User Type',$this->app->auth->model['scope'])
						->addMoreInfo('User_id',$this->app->auth->model->id)
						->addMoreInfo('User',$this->app->auth->model['name'])
						->addMoreInfo('isLoggedIn',$this->app->auth->isLoggedIn())
						->addMoreInfo('page',$this->app->page)
						;

		}

		if($this->app->is_admin){
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

			if(abs(strtotime(date('Y-m-d H:i:s',strtotime($this->api->now))) - strtotime(date('Y-m-d H:i:s'))) > 10) {
				$breadcrumbs[] = ['title'=>$this->app->now, 'url'=>'#', 'active'=>'label-danger'];
			}

			$br = $this->app->layout->add('CompleteLister',null,'breadcrumb',['layout/cube','breadcrumb']);
			$br->setSource($breadcrumbs);

			$intro_vp = $this->app->add('VirtualPage');
			$intro_vp->set(function($page){
				if(!$doc=$this->app->getConfig('documentation/'.$this->app->page,false)){
					$page->add('View_Error')->set('No Documentation is defined for this page yet ['.$this->app->page.']');
					throw $this->exception('','StopInit');
					return;
				}

				if(strpos($doc, 'http') ===0){
					if(strpos($doc, 'youtube')){
						$content= '<iframe width="560" height="315" src="'.$doc.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
					}else{
						$content = file_get_contents($doc);
					}
				}else{
					$content = $doc;
				}

				$page->add('View')->setHTML($content);

			});
			if(!$this->app->isAjaxOutput()){
				$this->js('click',$this->js()->univ()->frameURL('Documentation / Help',$intro_vp->getURL()))->_selector('.run-page-intro');
			}
			// $this->js(true)->_load('intro.min')->_css('introjs.min')->_load('xintroJS');
			// $this->js('click',$this->js()->univ()->runIntro())->_selector('.run-page-intro');
		}

	}
}