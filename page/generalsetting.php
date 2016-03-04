<?php
namespace xepan\base;

class page_generalsetting extends \Page{
	public $title="General Settings";
	function init(){
		parent::init();
		$user_view=$this->add('xepan\hr\CRUD',null,'usermanagment',['view/setting/user-grid']);
		$user_view->setModel('xepan\base\User');


		$setiingview=$this->add('xepan\hr\CRUD',['action_page'=>'xepan_base_general_email'],'general_setting',['view/setting/email-setting-grid']);
		$setiingview->setModel('xepan\base\Epan_EmailSetting');

		$this->add('xepan\base\View_Emails',null,'email');
	}
	function defaultTemplate(){
		return ['page/general-setting'];
	}
}