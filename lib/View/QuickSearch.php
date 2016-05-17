<?php

namespace xepan\base;


class View_QuickSearch extends \View {
	
	function init(){
		parent::init();

		$f = $this->add('Form',null,null,['form/minimal']);
		$f->addField('Line','search_xepan','search xEpan');
		$f->addSubmit('Search')->addClass('btn btn-primary');

		$result = $this->add('View');
		
		if($_GET[$f->name.'_term']){
			$this->app->hook('quick_searched',[$_GET[$f->name.'_term'],$result]);
		}

		if($f->isSubmitted()){
			$result->js()->reload([$f->name.'_term'=>$f['search_xepan']])->execute();
		}

	}
}