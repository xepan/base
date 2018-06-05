<?php

namespace xepan\base;


class Model_Config_DocumentOtherInfo extends \xepan\base\Model_ConfigJsonModel{
	public $fields =[
						'for'=>'Line',
						'name'=>"Line",
						'type'=>"DropDown",
						'possible_values'=>"Text",
						'is_mandatory'=>'CheckBox',
						'conditional_binding'=>'Text'
					];
	public $config_key = 'Document_Other_Info_Fields';
	public $application='base';

	function init(){
		parent::init();
		$this->getElement('type')->enum(['Line','Text','DatePicker']);
		$this->getElement('conditional_binding')->hint("Enter sperated lines for fields \n{'Value A':{'Field B', 'Field C','Field D'},'Value B':{'Field A', 'Field F'}}");
		$this->getElement('possible_values')->hint('Comma Seperated Values For DropDown type');
		$this->getElement('for')->hint('Document Type like Quotation/SalesOrde/SalesInvoice/PurhcaseOrder etc');
	}

}