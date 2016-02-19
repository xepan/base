<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class page_contact_profile extends \Page {
	public $title='Contact Profile';

	function init(){
		parent::init();

		$contact = $this->add('xepan\base\Model_Contact')->load($this->api->stickyGET('id'));
		$this->add('xepan\base\View_Profile',['action'=>$this->api->stickyGET('action')?:'view'])->setModel($contact,['first_name','last_name','type']);

		$d = $this->add('xepan\base\View_Document',
				[
					'action'=>'view', // add/edit
					'id_fields_in_view'=>'["all"]/["post_id","field2_id"]',
					'allow_many_on_add' => false // Only visible if editinng
				]
			);
		$d->setModel($contact,['view_field_here'],['edit_fields_here']);
		$class_object  = $d->addMany('Emails_relation','on_spot','class_default_minicrud',['fields_grid','fields_form'],'class_options','template_for_class');
		
	}
}
