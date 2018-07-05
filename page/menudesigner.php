<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xavoc.com
* 
*/

namespace xepan\base;

class page_menudesigner extends \xepan\base\Page{
	public $title='Menu Designer';

	function init(){
		parent::init();
		
		$this->model = $this->add("xepan\base\Model_Config_Menus");
		$this->model->tryLoadAny();
	}

	function page_index(){
			

		$crud = $this->add('xepan\hr\CRUD');
		$crud->setModel($this->model,['id','name','is_set']);
		$crud->grid->addColumn('Button','design');

		if($_GET['design']){
			$this->app->redirect($this->app->url('./design',['designid'=>$_GET['design']]));
		}
	}

	function page_design(){
		$id = $this->app->stickyGET('designid');
		$this->model->load($id);
		
		$available_menus = [];
		foreach($this->app->xepan_app_initiators as $app_namespace =>$app_inits){
            if($this->app->getConfig('hidden_'.str_replace("\\", "_", $app_namespace),false)){
                continue;
            }

            if($app_inits->hasMethod('getTopApplicationMenu')){
                $arr = $app_inits->getTopApplicationMenu();
                $available_menus = array_merge($available_menus,$arr);
            }
        }

        $v = $this->add('View');
        $v->js(true)->_load('menudesigner')->menudesigner(['designing_menu'=>$this->model['name'],'available_menus'=>$available_menus,'saved_menus'=>[]]);

	}
}
