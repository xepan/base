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
	public $options = [
			"border"=> [
				'color'=> '#ddd',
				'width'=> 3
			],
			'colors'=> ['#a3a948', '#edb92e', '#f85931', '#ce1836', '#009989'],
			'text'=> '#fff',
			'size'=> 60,
			'margin'=> 5,
			'middlename'=> false,
			'uppercase'=> true
		];

	function init(){
		parent::init();

		if($this->owner instanceof \Lister){
			$grid = $this->owner;
		}elseif($this->owner instanceof \CRUD){
			$grid= $this->owner->grid;
		}else{
			throw new \Exception($this->owner, 1);
			
		}

		$style = "
				color: ".$this->options['text'].";
				border: ".$this->options['border']['width'] ."px solid ". $this->options['border']['color'].";
				display: inline-block;
				font-family: Arial,Helvetica Neue, Helvetica, sans-serif;
				font-size: ". $this->options['size'] * 0.35 ."px;
				border-radius: ".$this->options['size']."px;
				width: ".$this->options['size']."px;
				max-width: ".$this->options['size']."px;
				height: ".$this->options['size']."px;
				line-height: ".$this->options['size']."px;
				margin: ".$this->options['margin']."px;
				text-align: center;
				text-transform : ".($this->options['uppercase'] ? "uppercase" : "").";";

		$style= preg_replace("/[\n\t]/", "", $style);

		$grid->addHook('formatRow',function($g)use($style){			
			if(!$g->model['image']){
				$initials= trim($g->model[$this->name_field]);
				preg_match_all("/[A-Z]/", ucwords(strtolower($initials)), $initials);
				if(!$this->options['middlename'] && count($initials[0])>2)
					$initials=[[$initials[0][0],$initials[0][count($initials[0])-1]]];
				$initials = implode("", $initials[0]);
				$style .= "background-color: ".$this->options['colors'][rand(0,count($this->options['colors'])-1)].";";
				$g->current_row_html['avatar']= "<div class='namebadge' style=\"position:relative; float:left; ".$style."\">".$initials."</div>";
			} 
		});
	}
}
