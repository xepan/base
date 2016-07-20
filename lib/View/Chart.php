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

		$this->options['element'] = $this->getJSId();
	}

	function setLabels($labels){
		$this->options['labels'] = $labels;
		return $this;
	}

	function setOption($key,$value){
		$this->options[$key] = $value;
		return $this;
	}

	function setChartType($charttype){
		$this->type = $charttype;
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

	function setXAxis($x_Axis){
		$this->options['xkey']=$x_Axis;
		return $this;
	}

	function setYAxis($y_Axis){
		$this->options['ykeys']=$y_Axis;
		return $this;
	}

	function setLineColors($colors =['#ffc107', '#03a9f4']){
		$this->options['lineColors'] = $colors;
	}

	function setXAxisTitle($x_Axis_Title){
		$this->options['xAxis']['title']['text']=$x_Axis_Title;
		return $this;
	}

	function setYAxisTitle($y_Axis){
		$this->options['yAxis']['title']['text']=$y_Axis;
		return $this;	
	}

	function setData($data){
		// if(is_array($data))
		// 	$data = json_encode($data);
		$this->options['data'] = $data;
		return $this;
	}

	function debug(){
		$this->_debug=true;
	}

	function render(){
		$this->validateOptions();
		
		if($this->_debug){
			echo "<pre>";
			print_r($this->data);
			echo "</pre>";
		}

		// var_dump($this->options);
		// exit;
		$this->js(true)
					->_load('raphael-min')
					->_load('morris')
					;

		$this->js(true)->_library($this->library)->{$this->type}($this->options);
		parent::render();
	}

	function defaultTemplate(){
		return array('view/chart');
	}

	function validateOptions(){
		if(!trim($this->library))
			throw new \Exception("must defined library", 1);

		if(!trim($this->type))
			throw new \Exception("must defined Graph Type", 1);

	}	
}