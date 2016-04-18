<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/


namespace xepan\base;


class page_installer_generatesql extends \Page {
	public $title='Generate Installer SQL';

	function init(){
		parent::init();
		
		$form= $this->add('Form');
		$form->addField('username');
		$form->addField('password','password');
		$form->onSubmit(function($f){
			if(!$this->app->auth->verifyCredentials($f['username'],$f['password'])){
				$f->displayError('username','Sorry, you cant reset database');
			}

			try{
				$user = clone $this->app->auth->model;
				$this->api->db->beginTransaction();
				$this->app->db->dsql()->expr('SET FOREIGN_KEY_CHECKS = 0;')->execute();
				foreach ($this->app->xepan_addons as $addon) {
					$this->app->xepan_app_initiators[$addon]->generateInstaller();	
				}
				$this->app->db->dsql()->expr('SET FOREIGN_KEY_CHECKS = 1;')->execute();        
				$this->api->db->commit();
			}catch(\Exception_StopInit $e){

			}catch(\Exception $e){
				$this->api->db->rollback();
				$this->app->auth->login($user);
				throw $e;
			}

			return "SQL Generated";

		});
	}
}
