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

		/*Default Balance Sheet Heads and groups*/
       $this->add('xepan\accounts\Model_BalanceSheet')->loadDefaults();
       $this->add('xepan\accounts\Model_Group')->loadDefaults();
       $this->add('xepan\accounts\Model_Ledger')->loadDefaults();		
	}
}
