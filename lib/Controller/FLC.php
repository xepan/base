<?php


namespace xepan\base;

// Form layout Creator [FLC]

/*
	Either owner should have function 
		createLayout
	Or call explicit layout() function 
*/
class Controller_FLC extends \AbstractController {
	
	public $collepsible_panel = false; 

	function init(){
		parent::init();

		// create array first
		// row for each group if not exists
		// add title from third part as full width
		// create column of second arg width 
		// create string from created array
		// create template with created source
		// set layout for form 

	}

	function makePanelsCoppalsible(){
		$this->collepsible_panel = true;
	}

	


	// $array = ['field.class(attr=value[, ]attr=value)'=>'group~width~Title of Group or bl']
	function layout($array=null){
		$rows=[];

		foreach ($array as $field => $detail) {
			list($group,$width,$title) = explode("~", $detail);

			if(!isset($rows[$group])) $rows[$group]=[];
			if(!isset($rows[$group][$title]) $rows[$group]['title']=$title;
			$rows[$group][$width]= $field;
		}

		$template_str="";
		foreach ($rows as $row) {
			
		}

	}
}