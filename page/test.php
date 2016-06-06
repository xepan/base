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

		$items = $this->add("xepan\commerce\Model_Item");
		foreach ($items as $item) {
			if(!$item['designs'])
				continue;
			
			$replace = str_replace("\/upload", "websites\/www\/upload", $item['designs']);
			$item['designs'] = $replace;
			$item->save();			
		}
		// $design_array = json_decode($item['designs'],true);
		// $design = $design_array['design']; 
		// echo $item['designs'];
		// echo "<pre>";
		// print_r($design);
		// foreach ($design as $page) {
		// 	$design = json_encode($page);
		// }
		// $g=$this->add('Grid');
		// $g->setModel('xepan\base\Epan');
		// $g->addQuickSearch(['name']);

		// $f= $this->add('Form');
		// $f->addField('line','q');
		// if($f->isSubmitted()){
		// 	throw new \Exception($f['q'], 1);
			
		// }
	}
}
