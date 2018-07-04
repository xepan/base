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

	function page_index(){
			
		$model = $this->add("xepan\base\Model_Config_Menus");
		$model->tryLoadAny();

		$crud = $this->add('xepan\hr\CRUD');
		$crud->setModel($model,['id','name']);
		$crud->grid->addColumn('Button','design');

		if($_GET['design']){
			$this->app->redirect($this->app->url('./design',['designid'=>$_GET['design']]));
		}
	}

	function page_design(){
		$id = $this->app->stickyGET('designid');
		
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
        $this->js(true)->_load('menudesigner')->univ()->menudesigner($v);

	}
}
