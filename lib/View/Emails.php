<?php
namespace xepan\base;

class View_Emails extends \View{
	function init(){
		parent::init();
	}
	function defaultTemplate(){
		return ['view/email'];
	}
}