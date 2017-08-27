<?php


namespace xepan\base;

// Form layout Creator [FLC]

/*
	$form->add('xepan\base\Controller_FLC')
		->showLables(true)
		->makePanelsCoppalsible(true)
		->layout([
				'first_name~FIield New Cpation'=>'Name Section~c1~4',
				'nick_name'=>'c2~4',
				'last_name'=>'c3~4',
				'city'=>'Location~c1~4~closed', // closed to make panel default collapsed
				'state'=>'c2~4',
				'country'=>'c3~4'
			]);
*/
class Controller_FLC extends \AbstractController {
	
	public $collepsible_panel = false; 
	public $add_lables = true; 
	public $addContent = false; 

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
		return $this;
	}

	function showLables($show=true){
		$this->add_lables = $show;
		return $this;
	}

	function addContentSpot(){
		$this->addContent=true;
		return $this;
	}


	/**
	 * 
		'first_name'=>'Name Section~c1~6',
		'nick_name'=>'~c1',
		'last_name'=>'~c2~6',
		'city'=>'Location~c1~4',
		'state'=>'~c2~4',
		'country'=>'~c3~4',
	 */
	function layout($array=null){
		$rows=[];
		$collapsed_sections=[];

		$last=null;
		foreach ($array as $field => $detail) {

			list($title,$column,$width,$collapsed) = explode("~", $detail);

			if(strlen($title)<3){
				$width=$column?:'auto';
				$column=$title;
				$title='';
			}

			if($title=='') $title= isset($last_title)?$last_title:'';
			$last_title = $title;

			if(!isset($rows[$title])) $rows[$title]=[];

			if(!isset($rows[$title][$column])) $rows[$title][$column]=['width'=>$width, 'fields'=>[]];

			$rows[$title][$column]['fields'][] = $field;

			if($collapsed=='closed'){
				$collapsed_sections[]=$title;
			}

		}

		// echo "<pre>";
		// print_r($rows);
		// echo "</pre>";

		$template_str="";
		foreach ($rows as $title => $row) {
			$template_str .= "<div class='row panel panel-default xepan-flc-form'>";
			if(!is_numeric($title)){
				$id=$this->app->normalizeName($title);
				$data_str="";
				$collapse_in_handler_class= "";
				$collapse_in= "";
				$cursor="";
				if($this->collepsible_panel){
					$data_str ="  data-toggle='collapse' data-target='#$id'";
					$collapse_in="collapse in";
					
					if(in_array($title, $collapsed_sections)){
						$collapse_in_handler_class= "collapsed";
						$collapse_in="collapse";						
					}

					$cursor="style='cursor:pointer'";
				}
				$template_str .= "<div class='panel-heading $collapse_in_handler_class' $data_str $cursor>$title</div>";
				$template_str .="<div class='panel-body $collapse_in' id='$id'>";
			}
				foreach ($row as $col) {
					$template_str.="<div class='col-md-".$col['width']."'>";
						foreach ($col['fields'] as $field) {
							$field_arr=explode("~", $field);
							$field=$field_arr[0];
							$field_caption=isset($field_arr[1])?$field_arr[1]:ucwords(str_replace('_', ' ', $field));

							if($this->add_lables){
								$template_str.=$field_caption.'<br/>';
							}
								$template_str.= '<div class="atk-form-field atk-form-row">{$'.$field.'}</div>';
							if($this->add_lables){
							}
						}
					$template_str.="</div>";
				}
			if(!is_numeric($title)){
				$template_str .="</div>";
			}
			$template_str .= "</div>";
		}

		if($this->addContent){
			$template_str .='<div>{$Content}</div>';
		}


		$t = $this->add('GiTemplate')->loadTemplateFromString($template_str);
		$this->owner->setLayout($t);

	}

	function layoutComplex(){
		// '1.row'=>[
		// 			'Title of the Row'
		// 			'1.col_8'=>[
		// 				'Title of Col1 Section'
		// 				'1.row'=>[
		// 					'1.col_6'=>['{$first_name}','{$nick_name}'],
		// 					'2.col_6'=>['{$last_name}']
		// 				]
		// 			]
		// 			'2.col_4'=>[
		// 				'Title of col2 Section'
		// 			]
		// 		]
	}
}