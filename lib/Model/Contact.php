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
		$this->hasOne('xepan\base\Contact','created_by_id');
		$this->hasOne('xepan\base\User',null,'username');

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
		$this->addField('post')->caption('Post');
		$this->addField('website');
		
		$this->addField('created_at')->type('datetime')->defaultValue(@$this->app->now);
		$this->addField('updated_at')->type('datetime')->defaultValue(@$this->app->now);

		$this->addField('search_string')->type('text')->system(true)->defaultValue(null);
		// $this->addField('created_at')->type('datetime')->defaultValue($this->app->now);
		// $this->addField('updated_at')->type('datetime')->defaultValue($this->app->now);

		$this->add('xepan/filestore/Field_Image',['name'=>'image_id','deref_field'=>'thumb_url'])->allowHTML(true);

		$this->addExpression('name')->set($this->dsql()->expr('CONCAT([0]," ",[1])',[$this->getElement('first_name'),$this->getElement('last_name')]))->sortable(true);

		$this->hasMany('xepan\base\Contact_Email',null,null,'Emails');
		$this->hasMany('xepan\base\Contact_Phone',null,null,'Phones');
		$this->hasMany('xepan\base\Contact_Relation',null,null,'Relations');
		$this->hasMany('xepan\base\Contact_IM',null,null,'IMs');
		$this->hasMany('xepan\base\Contact_Event',null,null,'Events');

		$this->addExpression('emails_str')->set(function($m,$q){
			$x = $m->add('xepan\base\Model_Contact_Email',['table_alias'=>'emails_str']);
			return $x->addCondition('contact_id',$q->getField('id'))->_dsql()->del('fields')->field($q->expr('group_concat([0] SEPARATOR "<br/>")',[$x->getElement('value')]));
		})->allowHTML(true)->sortable(true);

		$this->addExpression('contacts_str')->set(function($m,$q){
			$x = $m->add('xepan\base\Model_Contact_Phone',['table_alias'=>'contacts_str']);
			return $x->addCondition('contact_id',$q->getField('id'))->_dsql()->del('fields')->field($q->expr('group_concat([0] SEPARATOR "<br/>")',[$x->getElement('value')]));
		})->allowHTML(true);

		$this->addExpression('online_status')->set(function($m,$q){
			return '"online"'; // or ideal or offline
		});

		$this->addHook('beforeSave',function($m){$m['updated_at']=$this->app->now;});

		$this->addHook('beforeDelete',[$this,'deleteContactEmails']);
		$this->addHook('beforeDelete',[$this,'deleteContactPhones']);
		$this->addHook('beforeDelete',[$this,'deleteContactRelations']);
		$this->addHook('beforeDelete',[$this,'deleteContactIMs']);
		$this->addHook('beforeDelete',[$this,'deleteContactEvents']);

		$this->addHook('beforeSave',function($m){$m['updated_at'] = $m->app->now;});

		$this->is([
				'epan_id|required',
				'first_name|to_trim|to_upper_words|required',
				'last_name|to_trim|to_upper_words',
				'user_id|unique_in_epan',
				'type|to_trim|required'
			]);
	}

	function deleteContactEmails(){
		$this->ref('Emails')->deleteAll();
	}
	function deleteContactPhones(){
		$this->ref('Phones')->deleteAll();
	}
	function deleteContactRelations(){
		$this->ref('Relations')->deleteAll();
	} 
	function deleteContactIMs(){
		$this->ref('IMs')->deleteAll();
	}
	function deleteContactEvents(){
		$this->ref('Events')->deleteAll();
	}

	function page_communication($page){		
		$communication = $this->add('xepan\communication\Model_Communication');
		$communication->addCondition(
						$communication->dsql()->orExpr()
						->where('from_id',$this->id)
						->where('to_id',$this->id)
					);

		$communication->setOrder('created_at','desc');
		$contact_id = $this->id;

		$lister=$page->add('xepan\communication\View_Lister_Communication',['contact_id'=>$contact_id],null,null);
		$lister->setModel($communication)->setOrder(['created_at desc','id desc']);
	}

	//load Logged In check for the user of contact loaded or not, 
	//mainly used  for online contact account
	function loadLoggedIn(){
		if($this->loaded()) $this->unload();
		if(!$this->api->auth->isLoggedIn()) return false;
		
		$this->addCondition('user_id',$this->api->auth->model->id);
		$this->tryLoadAny();
		if(!$this->loaded()) return false;
		return true;
	}


	function getEmails(){
		$emails = $this->ref('Emails')
								->_dsql()->del('fields')->field('value')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($emails)),false);
	}

	function getPhones(){
		$emails = $this->ref('Phones')
								->_dsql()->del('fields')->field('value')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($emails)),false);	
	}

	function user(){
		return $this->ref('user_id');
	}
}
