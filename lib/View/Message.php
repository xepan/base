<?php
namespace xepan\base;
class View_Message extends \CompleteLister{
	function init(){
		parent::init();

	}
	function setModel($m){
		$m = parent::setModel($m);
		if($this->model->count()->getOne() == 0){
			$v =  $this->add('H3',null,'no_record_found')->addClass('xepan-push text-center')->set('No New Message Found');
			// $this->template->trySet('no_record_found',$v->getHTML());
		}
	}

	function formatRow(){
		
		$this->add('xepan\base\Controller_Avatar',['options'=>['size'=>30,'border'=>['width'=>0]],'name_field'=>'contact']);		
		$this->current_row_html['name'] = $this->model['from_raw']['name'];
		// $this->current_row_html['message']  = $this->model['description'];
		$this->current_row_html['title']  = $this->model['title'];
		
		parent::formatRow();
	}

	function getJSID(){
		return "messageid";
	}

	function defaultTemplate(){
		return ['view/message'];
	}
}