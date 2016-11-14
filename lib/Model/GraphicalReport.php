<?php


namespace xepan\base;

class Model_GraphicalReport extends \xepan\base\Model_Table{
	public $table='graphical_report';
	public $widget_list = [];
	public $acl_type='GraphicalReport';
	public $status=['All'];
	public $actions=['All'=>['view','edit','delete','manage_widgets','manage_post_permissions']];

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Contact','created_by_id');
		$this->addField('name');
		$this->addField('permitted_post');

		$this->hasMany('xepan\base\GraphicalReport_Widget','graphical_report_id');
		$this->addExpression('status','"All"');

		$this->addHook('beforeSave',$this);

	}

	function beforeSave(){
		if(!$this->loaded()){
			$c = $this->add('xepan\base\Model_Contact');
			$c->loadLoggedIn();
			$this['created_by_id'] = $c->id;
		}
	} 

	function page_manage_widgets($page){
		$this->app->hook('widget_collection',[&$this->widget_list]);
		$m = $page->add('xepan\base\Model_GraphicalReport_Widget');
		$m->getElement('class_path')->enum($this->widget_list);

		$m->addCondition('graphical_report_id',$this->id);
		$c = $page->add('xepan\base\CRUD');
		$c->setModel($m);
	}

	function page_manage_post_permissions($page){
		$post = $this->add('xepan\hr\Model_Post');
		$post->addCondition('status','Active');

		$form = $page->add('Form');
		$post_field = $form->addField('xepan\base\DropDown','posts');
		$post_field->set(json_decode($this['permitted_post'],true));
		$post_field->setAttr(['multiple'=>'multiple']);
		$post_field->setModel($post);
		$post_field->addClass('xepan-push');
		$form->addSubmit('Permit Posts')->addClass('btn btn-primary');

		$permission_array = [];
		if($form->isSubmitted()){
			$permission_array = explode(',', $form['posts']);
			$this->manage_post_permissions($permission_array);
			return $page->js()->univ()->closeDialog();			
		}

	}

	function manage_post_permissions($permission_array){
		$this['permitted_post']= json_encode($permission_array);
		$this->save();		
		return true;
	}			
}