<?php

namespace xepan\base;

class Initiator extends \Controller_Addon {
	public $addon_name = 'xepan_base';

	function init(){
		parent::init();        

                if($this->app->is_admin){
                        $this->routePages('xepan_base');
                        $this->addLocation(array('template'=>'templates','js'=>'templates/js','css'=>'templates\css'))
                        ->setBaseURL('../vendor/xepan/base/')
                        ;

                        $this->app->today = date('Y-m-d');
                        $this->app->now   = date('Y-m-d H:i:s');

                        $auth = $this->app->add('BasicAuth',['login_layout_class'=>'xepan\base\Layout_Login']);
                        $auth->addHook('createForm',function($a,$p){

                            $p->add('HR');
                            $f = $p->add('Form',null,null,['form/minimal']);
                            $f->setLayout(['layout/xepanlogin','form_layout']);
                            $f->addField('Line','username','Email address');
                            $f->addField('Password','password','Password');
                            // $cc = $f->add('Columns');
                            // $cc->addColumn()->add('Button')->set('Log in')->addClass('atk-size-milli atk-swatch-green');
                            // $cc->addColumn()->addClass('atk-align-right')->addField('Checkbox','remember_me','Remember me');
                            $this->breakHook($f);

                        });
                        $user = $this->add('xepan\base\Model_User_Active');
                        $user->addCondition('scope',['AdminUser','SuperUser']);
                        $auth->setModel($user,'username','password');
                        $auth->check();

                        $this->app->epan = $auth->model->ref('epan_id');
                        
                        $this->app->jui->addStaticInclude('xepan_jui');
                        $this->api->js(true)->_selector('.sparkline')->sparkline('html', ['enableTagOptions' => true]);
                }else{
                        $this->routePages('xepan_');
                        $this->addLocation(array('template'=>'templates','js'=>'js'))
                        ->setBaseURL('./vendor/xepan/base/')
                        ;


                        $this->app->today = date('Y-m-d');
                        $this->app->now   = date('Y-m-d H:i:s');

                }

	}

}
