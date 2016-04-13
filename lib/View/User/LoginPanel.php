<?php
namespace xepan\base;

class View_User_LoginPanel extends \View{
	function init(){
		parent::init();
        $f = $this->add('Form',null,null,['form/minimal']);
        $f->setLayout(['view/login-panel']);
        $f->addField('Line','username','Email address');
        $f->addField('Password','password','Password');
    	$auth=$this->app->auth;
 
        if($f->isSubmitted()){
        	if(!($credential = $auth->verifyCredentials($f['username'],$f['password'])))
					$f->displayError('username','Wrong Credentials');
					$user = $this->add('xepan\base\Model_User')->load($credential);
					if($user['status']=='Inactive')
						$f->displayError('username','Please Activate Your Account First');
					$auth->login($f['username']);
					$this->app->redirect($this->api->url(null))->execute();
					$this->js()->reload()->execute();
        }
	}
}