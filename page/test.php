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

class page_test extends \Page {
	public $title='Page Title';

	function init(){
		parent::init();
		$email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')
								->addCondition('imap_email_password','not',null);

		foreach ($email_settings as $email_setting) {
			$cont = $this->add('xepan\communication\Controller_ReadEmail',['email_setting'=>$email_setting]);
			$mbs = $cont->getMailBoxes();
			foreach ($mbs as $mb) {
				var_dump($cont->fetch($mb));
			}
		}
	}
}
