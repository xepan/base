<?php
namespace xepan\base;
class View_Tool extends \View{
	public $options=[];

	function setModel($model,$fields=null){
		$m = parent::setModel($model,$fields);
		$this->add('xepan\base\Controller_Tool_Optionhelper');
		return $m;
	}
}