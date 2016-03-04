<?php

/**
* description: Adds Avatar in template at {avatar} spot
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Controller_Avatar extends \AbstractController{

	public $name_field = 'name';

	function init(){
		parent::init();

		if($this->owner instanceof \Grid){
			$grid = $this->owner;
		}elseif($this->owner instanceof \CRUD){
			$grid= $this->owner->grid;
		}

		$grid->addHook('formatRow',function($g){			
			if(!$g->model['image']) 
				$g->current_row_html['avatar']= "<div class='namebadge' style='position:relative; max-width:50px;float:left'>".$g->model[$this->name_field]."</div>";
		});

		$this->app->js(true)->_load('jquery.nameBadges')->_selector('.namebadge')->nameBadge(['size'=>45,'middlename'=>false,'uppercase'=>true,'border'=>['width'=>1]]);
	}
}
