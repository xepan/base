<?php

namespace xepan\base;

class View_Wizard_Step extends \View {
	function setTitle($title){
		$this->template->set('title',$title);
		return $this;
	}

	function setMessage($msg){
		$this->template->set('message',$msg);
		return $this;
	}

	function setHelpURL($url){
		$this->template->set('url',$url);
		return $this;
	}

	function setAction($title,$action){
		$button = $this->add('Button',null,'action_spot')->set($title)->addClass('btn btn-primary btn-sm');
		$button->js('click',$action);
		return $this;
	}

	function defaultTemplate(){
		return ['view\wizardsetup'];
	}
}