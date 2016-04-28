<?php

namespace xepan\base;

class View_Communication extends \CompleteLister{
	function init(){
		parent::init();
	}

	function formatRow(){
		$to_mail = json_decode($this->model['to_raw'],true);
		$to_lister = $this->app->add('CompleteLister',null,null,['view/communication1','to_lister']);
		$to_lister->setSource($to_mail);
			
		// $cc_raw = json_decode($this->model['cc'],true);
		// $cc_lister = $this->app->add('CompleteLister',null,null,['view/communication','cc_lister']);
		// $cc_lister->setSource($cc_raw);

		// echo"<pre>";
		// print_r($to_mail);
		// exit;

		$this->current_row_html['description']=$this->current_row['description'];
		$this->current_row_html['attachment'] = $this->model['attachment_count']?'<span><i style="color:green" class="fa fa-paperclip"></i></span>':'';
		$this->current_row_html['to_lister'] = $to_lister->getHtml();
		// $this->current_row_html['cc_lister'] = $cc_lister->getHtml();
		return parent::formatRow();
	}

	function defaultTemplate(){
		return['view\communication1'];
	}
}