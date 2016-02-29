<?php

namespace xepan\base;

class Initiator extends \Controller_Addon {
	public $addon_name = 'xepan_base';

	function init(){
		parent::init();

                if($this->app->is_admin){
                        $this->routePages('xepan_base');
                        $this->addLocation(array('template'=>'templates','js'=>'js'))
                        ->setBaseURL('../vendor/xepan/base/')
                        ;

                        $this->app->today = date('Y-m-d');
                        $this->app->now   = date('Y-m-d H:i:s');

                        $auth = $this->app->add('BasicAuth');
                        $auth->setModel('xepan\base\User_Active','username','password');
                        $auth->check();

                        $this->app->epan = $auth->model->ref('epan_id');
                        
                        $this->app->jui->addStaticInclude('xepan_jui');
                        $this->api->js(true)->_selector('.sparkline')->sparkline();
                }else{
                        $this->routePages('xepan');
                        $this->addLocation(array('template'=>'templates','js'=>'js'))
                        ->setBaseURL('./vendor/xepan/base/')
                        ;

                        $this->app->today = date('Y-m-d');
                        $this->app->now   = date('Y-m-d H:i:s');

                }

	}

}
