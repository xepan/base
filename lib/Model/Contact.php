<?php

/**
* description: Contact serves as Base model for all models that relates to any human contact
* Let it be lead, customer, supplier or any other contact in any application.
* This contact model stores all basic possible details in this table and leave specific implementation
* for Model extending this Model by joining other tables
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact extends \xepan\base\Model_Table{
	public $table='contact';

	public $status=[];
	public $actions=[];

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');
		$this->addField('type');
		
		$this->addField('first_name');
		$this->addField('last_name');

		$this->addField('address')->type('text');
		$this->addField('city');
		$this->addField('state');
		$this->addField('country');
		$this->addField('pin_code');
		$this->addField('status')->enum($this->status)->mandatory(true)->system(true);

		$this->addField('organization');
		$this->addField('post');
		$this->addField('website');

		$this->add('filestore/Field_Image','image_id')->allowHTML(true);

		$this->addExpression('name')->set($this->dsql()->expr('CONCAT([0]," ",[1])',[$this->getElement('first_name'),$this->getElement('last_name')]));

		$this->hasMany('xepan\base\Contact_Email',null,null,'Emails');
		$this->hasMany('xepan\base\Contact_Phone',null,null,'Phones');
		$this->hasMany('xepan\base\Contact_Relation',null,null,'Relations');
		$this->hasMany('xepan\base\Contact_IM',null,null,'IMs');
		$this->hasMany('xepan\base\Contact_Event',null,null,'Events');

		$this->addExpression('emails_str')->set(function($m,$q){
			$x = $m->add('xepan\base\Model_Contact_Email',['table_alias'=>'emails_str']);
			return $x->addCondition('contact_id',$q->getField('id'))->_dsql()->del('fields')->field($q->expr('group_concat([0],"<br/>")',[$x->getElement('value')]));
		})->allowHTML(true);

		$this->addExpression('contacts_str')->set(function($m,$q){
			$x = $m->add('xepan\base\Model_Contact_Phone',['table_alias'=>'contacts_str']);
			return $x->addCondition('contact_id',$q->getField('id'))->_dsql()->del('fields')->field($q->expr('group_concat([0],"<br/>")',[$x->getElement('value')]));
		})->allowHTML(true);


		$this->addExpression('avatar','""')->allowHTML(true);
		$this->addHook('afterLoad',function($m){
			if(!$m['image_id']){
				$m['avatar'] = "<div class='namebadge' style='position:relative; max-width:50px;float:left'>".$m['first_name'].' '.$m['last_name']."</div>";
			}
		});

		if(!isset($this->app->avatar_loaded)){
			$this->app->js(true)->_load('jquery.nameBadges')->_selector('.namebadge')->nameBadge(['size'=>45,'middlename'=>false,'uppercase'=>true,'border'=>['width'=>1]]);
			$this->app->avatar_loaded = true;
		}

	}
}
