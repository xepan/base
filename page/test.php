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

		$g=$this->add('Grid');
		$g->setModel('xepan\base\Epan');
		$g->addQuickSearch(['name']);

		$f= $this->add('Form');
		$f->addField('line','q');
		if($f->isSubmitted()){
			throw new \Exception($f['q'], 1);
			
		}
	}
}
