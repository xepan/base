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
	public $row_edit=true;
	public $row_delete=true;

	function init(){
		parent::init();

	}

	function defaultTemplate(){
		if($this->defaultTemplate) return $this->defaultTemplate;
		return parent::defaultTemplate();
	}
	
	function precacheTemplate(){
		if($this->defaultTemplate) return;		
		return parent::precacheTemplate();
	}

	function formatRow(){

	    parent::formatRow();

	    if($this->row_edit)
		    $this->current_row_html['edit']= '<a class="table-link pb_edit" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a>';
		else
			$this->current_row_html['edit']= '<span class="fa-stack table-link"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span>';

		if($this->row_delete)
		    $this->current_row_html['delete']= '<a class="table-link danger do-delete" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span></a>';
		else
		    $this->current_row_html['delete']= '<span class="table-link fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span>';
	}

	function applyTDParams($field, &$row_template = null)
    {
        // data row template by default
        if (!$row_template) {
            $row_template = &$this->row_t;
        }

        // setting cell parameters (tdparam)
        $tdparam = @$this->tdparam[$this->getCurrentIndex()][$field];
        $tdparam_str = '';
        if (is_array($tdparam)) {
            if (is_array($tdparam['style'])) {
                $tdparam_str .= 'style="';
                foreach ($tdparam['style'] as $key=>$value) {
                    $tdparam_str .= $key . ':' . $value . ';';
                }
                $tdparam_str .= '" ';
                unset($tdparam['style']);
            }

            //walking and combining string
            foreach ($tdparam as $id=>$value) {
                $tdparam_str .= $id . '="' . $value . '" ';
            }

            // set TD param to appropriate row template
            $row_template->trySet("tdparam_$field", trim($tdparam_str));
        }
    }

}
