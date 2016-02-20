<?php

/**
* description: xEPAN Grid, lets you defined template by options
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Grid extends \Grid{

	public $defaultTemplate=null;

	function init(){
		parent::init();

	}

	function defaultTemplate(){
		if($this->defaultTemplate) return $this->defaultTemplate;
		return parent::defaultTemplate();
	}
	
	function precacheTemplate(){

	}

	function formatRow(){
		
	 //    $this->columns['edit']['icon'] = '<i class="x fa fa-pencil fa-inverse" ></i>&nbsp;';
	 //    $this->columns['edit']['descr'] = "";
		// // throw new \Exception($this->columns['edit']['icon']);
	 //    parent::formatRow();

	    $this->current_row_html['delete']= '<a class="table-link danger do-delete" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span></a>';
	    $this->current_row_html['edit']= '<a class="table-link pb_edit" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a>';

	}

}
