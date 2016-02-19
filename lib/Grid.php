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

	public $template_option=null;

	function init(){
		parent::init();
		$this->addHook('formatRow',function($grid){

            if($grid->hasColumn('delete')){
                $grid->columns['delete']['descr']='<a class="table-link danger" href="#"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span></a>';
            }
            // if($grid->hasColumn('edit')){
            // 	      $grid->columns['edit']['icon']='<i class="icon-'.
            //         	$grid->columns['test ']['icon'].'"></i>';
            //     // $grid->columns['edit']['descr']='<a class="table-link danger" href="#"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a>';
            // }
        });
	}
	function defaultTemplate(){
		if($this->template_option) return $this->template_option;
		return parent::defaultTemplate();
	}
	
	function precacheTemplate(){

	}
}
