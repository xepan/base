<?php
namespace xepan\base;

class Tool_LoginPanel extends \xepan\cms\View_Tool{
	function init(){
		parent::init();
        $f = $this->add('Form',null,null,['form/minimal']);
        $f->setLayout(['view/tool/login-panel']);
        $f->addField('Line','username','Email address');
        $f->addField('Password','password','Password');
    	$auth=$this->app->auth;
     //    $f->setModel($auth->model,'username','password');
    	// throw new \Exception($auth, 1);

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
	// function setModel($model){
	// 	parent::setModel($model);
	// }
	// function defaultTemplate(){
	// 	return ['view/tool/login-panel'];
	// }
}