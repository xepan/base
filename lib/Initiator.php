<?php

namespace xepan\base;

class Initiator extends \Controller_Addon {
	public $addon_name = 'xepan_base';

	function init(){
		parent::init();
		$this->routePages('xepan_base');
		$this->addLocation(array('template'=>'templates','js'=>'js'))
        ->setBaseURL('../../vendor/xepan/base/');
        ;

        $auth = $this->app->add('BasicAuth');
        $auth->setModel('xepan\base\User_Active','username','password');
        $auth->check();

        $this->app->epan = $auth->model->ref('epan_id');
        $this->app->jui->addStaticInclude('xepan_jui');

	}

	function installEvilVirus($white_list_pages=[])
    {
        if(in_array($this->app->page, $white_list_pages)) return;
        $this->app->addHook('beforeObjectInit',function($o,$e){
            $e->addHook('afterAdd',function($o,$e){
                if(!$e instanceof xepan\base\Model_Table)return;
                if($e->hasElement('epan_id'))$e->addCondition('epan_id',$this->app->epan->id);
            });
        });
    }
}
