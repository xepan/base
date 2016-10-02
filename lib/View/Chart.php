<?php

/**
* description: Chart View
* 
* @author : Rk Sinha
* @email : rksinha.btech@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class View_Chart extends \View{
	public $options = ["resize"=> true];
	var $_debug;
	public $data=array();
	private $xAxis=array();
	private $yAxis=array();
	private $library;
	private $type;

	function init(){
		parent::init();
		$this->_debug=false;

		$this->options['bindto'] = '#'.$this->getJSId();
	}

	function setLabels($labels){
		$this->options['labels'] = $labels;
		return $this;
	}

	function setOption($key,$value){
		$this->options[$key] = $value;
		return $this;
	}

	function setXLabelAngle($angle=0){
		$this->options['xLabelAngle'] = $angle;
		return $this;
	}

	function setChartType($charttype){
		$this->setType($charttype);
		return $this;
	}

	function setLibrary($library_name){
		$this->library = $library_name;
		return $this;
	}

	function setElement($element_name){
		$this->element = $element_name;
		return $this;
	}

	function setTitle($title,$title_x_posistion=-20, $subtitle="",$subtitle_x_position=-20){
		$this->options['title']=array(
			'text'=>$title,
			'x'=>$title_x_posistion
			);
		$this->options['subtitle']=array(
			'text'=>$subtitle,
			'x'=>$subtitle_x_position
			);
		return $this;
	}

	function setXAxis($x_Axis_field, $type='category'){
		if(!isset($this->options['data']['keys'])) $this->options['data']['keys'] =[];
		$this->options['data']['keys']['x']=$x_Axis_field;
		if($type){
			if(!isset($this->options['axis'])) $this->options['axis'] =[];
			$this->options['axis']['x']=['type'=>$type];
		}

		$this->x_Axis_field = $x_Axis_field;

		return $this;
	}

	function setYAxises($y_Axis_fields){
		if(!isset($this->options['data']['keys'])) $this->options['data']['keys'] =[];
		$this->options['data']['keys']['value']=$y_Axis_fields;
		$this->y_Axis_fields = $y_Axis_fields;
		return $this;
	}

	function setLineColors($colors =['#ffc107', '#03a9f4']){
		$this->optionrecus['lineColors'] = $colors;
	}

	function setXAxisTitle($x_Axis_Title){
		$this->options['xAxis']['title']['text']=$x_Axis_Title;
		return $this;
	}

	function setYAxisTitle($y_Axis){
		$this->options['yAxis']['title']['text']=$y_Axis;
		return $this;	
	}

	function setType($type){
		$this->options['data']['type']=$type;
		$this->type = $type;
		return $this;
	}

	function setData($data){
		// if(is_array($data))
		// 	$data = json_encode($data);
		$this->options['data'] = $data;
		return $this;
	}

	function setGroup($groups){
		$this->options['data']['groups']=[$groups];
		return $this;
	}

	function mergeOptions($option){
		$this->options = array_merge($this->options,$option);
	}

	function debug(){
		$this->_debug=true;
		return $this;
	}

	function setModel($model,$x_Axis_field,$y_Axis_fields,$x_Axis_type='category'){
		$m = parent::setModel($model,array_merge($y_Axis_fields, [$x_Axis_field]));
		$this->x_Axis_field = $x_Axis_field;
		$this->y_Axis_fields = $y_Axis_fields;

		$this->setXAxis($x_Axis_field,$x_Axis_type);
		$this->setYAxises($y_Axis_fields);

		return $this; //not returning model 
	}

	function recursiveRender(){
		if($this->model){
			$data = $this->model->getRows();
			$this->options['data']['json']=$data;
		}

		if($this->model && $this->type=='pie'){
			$formatted_data=[];
			$formatted_values=[];
			foreach ($data as $row) {
				foreach ($this->y_Axis_fields as $fld) {
					$formatted_data[] = [$row[$this->x_Axis_field] => $row[$fld]];
					$formatted_values[] = $row[$this->x_Axis_field];
				}
			}
			unset($this->options['data']['keys']['x']);
			$this->options['data']['json']=$formatted_data;
			$this->options['data']['keys']['value']=$formatted_values;
		}

		parent::recursiveRender();
	}

	function render(){
		$this->validateOptions();
		
		if($this->_debug){
			echo "<pre>";
			print_r($this->options);
			echo "</pre>";
		}

		// var_dump($this->options);
		// exit;
		$this->js(true)
					->_load('d3.v3.min')
					->_load('c3.min')
					->_css('c3')
					;

		$this->js(true)->_library('c3')->generate($this->options);
		parent::render();
	}

	function defaultTemplate(){
		return array('view/chart');
	}

	function validateOptions(){
		// if(!trim($this->library))
		// 	throw new \Exception("must defined library", 1);

		// if(!trim($this->type))
		// 	throw new \Exception("must defined Graph Type", 1);

	}	
}