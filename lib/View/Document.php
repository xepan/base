<?php

/**
* description: View_Document is special View that helps to edit 
* any View in its own template by using same template as form layout
* It also helps in managing hasMany relations to be Viewed and Edit on same Level
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class View_Document extends \View{

	public $action='view'; // add/edit
	public $id_fields_in_view=[];
	public $allow_many_on_add=true;

	public $many_to_show=[];



	function init(){
		parent::init();
		
	}

	function setModel($model,$view_fields=null,$form_fields=null){
		return parent::setModel($model);
	}

	function addMany(
			$model,
			$view_class='xepan\base\Grid',$view_options=null,$view_spot='Content',$view_defaultTemplate=null,$view_fields=null,
			$class='xepan\base\MicroCRUD',$options=null,$spot='Content',$defaultTemplate=null,$fields=null
		){

		$view_prefix = '';
		if($this->action=='view') $view_prefix='view_';

		$v= $this->add(
				${$view_prefix.'class'},
				${$view_prefix.'options'},
				${$view_prefix.'spot'},
				${$view_prefix.'defaultTemplate'}
			);

		$v->setModel($model,is_CRU)
	}


}
