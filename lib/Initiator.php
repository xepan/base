<?php

namespace xepan\base;

class Initiator extends \Controller_Addon {
	public $addon_name = 'xepan_base';

	function init(){
		parent::init();        

                if($this->app->is_admin){
                        $this->routePages('xepan_base');
                        $this->addLocation(array('template'=>'templates','js'=>'templates/js','css'=>'templates/css'))
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
                        $auth->usePasswordEncryption('md5');
                        // $auth->debug();
                        // $auth->usePasswordEncryption();
                        $auth->setModel($user,'username','password');
                        $auth->addHook('loggedIn',function($auth,$user,$pass){
                            $auth->model['last_login_date'] = $this->app->now;
                            $auth->model->save();
                        });
                        $auth->check();
                        $this->app->epan = $auth->model->ref('epan_id');
                        $this->app->epan->config = $this->app->epan->ref('Configurations');
                        
                        $this->app->jui->addStaticInclude('xepan_jui');
                        $this->api->js(true)->_selector('.sparkline')->sparkline('html', ['enableTagOptions' => true]);
                }else{
                        $this->routePages('xepan_base');
                        $this->addLocation(array('template'=>'templates','js'=>'js'))
                        ->setBaseURL('./vendor/xepan/base/')
                        ;

                        $this->app->epan = $this->add('xepan\base\Model_Epan')->tryLoadAny();
                        $this->app->epan->config = $this->app->epan->ref('Configurations')->tryLoadAny();
                        
                        $this->app->today = date('Y-m-d');
                        $this->app->now   = date('Y-m-d H:i:s');

                }

	}

    function resetDB($write_sql=false){

        $this->app->old_epan = clone $this->app->epan;

        // Clear DB
        $truncate_models = ['Epan_Category','Epan','User','Epan_Configuration','Epan_EmailSetting','Epan_InstalledApplication','Application'];
        foreach ($truncate_models as $t) {
            $this->add('xepan\base\Model_'.$t)->deleteAll();
        }

        // Create default Epan_Category and Epan

        $epan_category = $this->add('xepan\base\Model_Epan_Category')
            ->set('name','default')
            ->save();

        $epan = $this->add('xepan\base\Model_Epan')
                    ->set('epan_category_id',$epan_category->id)
                    ->set('name','default')
                    ->save();

        $this->app->epan = $epan;
        $this->app->new_epan = clone $this->app->epan;

        // Create Default User
        $user = $this->add('xepan\base\Model_User_SuperUser');
        $this->app->auth->addEncryptionHook($user);
        $user=$user->set('username','admin@epan.in')
             ->set('scope','SuperUser')
             ->set('password','admin')
             ->set('epan_id',$epan->id)
             ->saveAs('xepan\base\Model_User_Active');

        $this->app->auth->login($user);

        if($write_sql){
            $dump = new \MySQLDump(new \mysqli('localhost', 'root', 'winserver', 'xepan2'));
            $dump->save(getcwd().'/../vendor/'.str_replace("\\",'/',__NAMESPACE__).'/export.sql');
        }


        // Do other tasks needed
        // Like empting any folder etc
    }

}
