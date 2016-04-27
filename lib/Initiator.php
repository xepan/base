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

                        $tinymce_addon_base_path=$this->app->locatePath('addons','tinymce\tinymce');
                        $this->addLocation(array('js'=>'.','css'=>'skins'))
                        ->setBasePath($tinymce_addon_base_path)
                        ->setBaseURL('../vendor/tinymce/tinymce/');


                        $elfinder_addon_base_path=$this->app->locatePath('addons','studio-42\elfinder');
                        $this->addLocation(array('js'=>'js','css'=>'css','image'=>'img'))
                        ->setBasePath($elfinder_addon_base_path)
                        ->setBaseURL('../vendor/studio-42/elfinder/');

                        $this->app->jui->addStylesheet('elfinder.full');
                        $this->app->jui->addStylesheet('elfindertheme');
                        $this->app->jui->addStaticInclude('elfinder.full');

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
                        $this->app->js(true)
                            ->_load('pnotify.custom.min')
                            ->_css('animate')
                            ->_css('pnotify.custom.min');
                        
                        $this->app->js(true,'PNotify.prototype.options.styling = "fontawesome"');
                        $this->app->js(true)->_library('PNotify.desktop')->permission();

                        $this->api->js(true)->_selector('.sparkline')->sparkline('html', ['enableTagOptions' => true]);
                }else{
                        $this->routePages('xepan_base');
                        $this->addLocation(array('template'=>'templates','js'=>'js'))
                        ->setBaseURL('./vendor/xepan/base/')
                        ;

                        $auth = $this->app->add('BasicAuth',['login_layout_class'=>'xepan\base\Layout_Login']);
                        $auth->usePasswordEncryption('md5');

                        $user = $this->add('xepan\base\Model_User_Active');
                        $user->addCondition('scope',['WebsiteUser']);
                        $auth->setModel($user,'username','password');

                        $url = "{$_SERVER['HTTP_HOST']}";
                        $sub_domain = $this->extract_subdomains($url)?:'web';
                        $this->app->epan = $this->add('xepan\base\Model_Epan')->tryLoadBy('name',$sub_domain);
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
            $dump->save(getcwd().'/../vendor/'.str_replace("\\",'/',__NAMESPACE__).'/install.sql');
        }


        // Do other tasks needed
        // Like empting any folder etc
    }


    function extract_domain($domain)
    {
        if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches))
        {
            return $matches['domain'];
        } else {
            return $domain;
        }
    }

    function extract_subdomains($domain)
    {
        $subdomains = $domain;
        $domain = $this->extract_domain($subdomains);
        $subdomains = rtrim(strstr($subdomains, $domain, true), '.');

        return $subdomains;
    }



}
