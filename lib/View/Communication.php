<?php

namespace xepan\base;

class View_Communication extends \CompleteLister{
	public $contact_id;
	
	function init(){
		parent::init();

		$self = $this;
		$self_url = $this->app->url(null,['cut_object'=>$this->name]);
		
		$vp = $this->add('VirtualPage');
		$vp->set(function($p)use($self,$self_url){

			$contact_id = $this->api->stickyGET('contact_id');	
			$model_contact = $this->add('xepan\base\Model_Contact');
			$model_contact->loadBy('id',$contact_id);
			$emails = $model_contact->getEmails();
			$email_string = implode(', ', $emails);
			
			$form = $p->add('Form');
			$form->setLayout('view\communicationform');
			$form->addField('title');
			$form->addField('dropdown','type')->setValueList(['Email'=>'Email','Call'=>'Call','Personal'=>'Personal']);
			$form->addField('xepan\base\RichText','body');
			$form->addField('checkbox','notifymail','');
			$form->addField('line','mails','To')->set($email_string);
			$form->addField('line','ccmails','CC');
			$form->addField('line','bccmails','Bcc');
			$form->addField('checkbox','notifysms','');
			$form->addField('phno');
			$form->addField('dropdown','fromemail')->setValueList(['info@xavoc.com'=>'info@xavoc.com','management@xavoc.com'=>'management@xavoc.com','support@xavoc.com'=>'support@xavoc.com','hr@xavoc.com'=>'hr@xavoc.com']);
			$form->addSubmit('Save');

			if($form->isSubmitted()){
				if($form['notifymail']){
					$email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')->tryLoadAny();
					$communication = $p->add('xepan\communication\Model_Communication_Abstract_Email');					
					$communication->setfrom($email_settings['from_email'],$email_settings['from_name']);
					$communication->getElement('status')->defaultValue('Draft');
					$communication->addCondition('communication_type','Email');
					$communication->addCondition('from_id',$this->app->employee->id);
					$communication->addCondition('to_id',$contact_id);
					
					$communication->setSubject($form['title']);
					$communication->setBody($form['body']);
					$communication->addTo($form['mails']);
					$communication->addBcc($form['bccmails']);
					$communication->addCc($form['ccmails']);
					$communication->send($email_settings);
					// $communication->save();
				}

				if($form['notifysms']){
					throw new \Exception("Notify Via Sms Is Yet To Made !");
				}
				
				$model_communication = $p->add('xepan\communication\Model_Communication');
				$model_communication['title'] = $form['title'];
				$model_communication['communication_type'] = $form['type'];
				$model_communication['description'] = $form['body'];
				$model_communication['to_id'] = $contact_id;
				$model_communication['from_id'] = $this->app->employee->id;
				$model_communication['status'] = '-';
				$model_communication->save();
				$this->app->db->commit();
				$form->js()->univ()->successMessage('Done')->execute();
			}
		});	
			

		$this->js('click',$this->js()->univ()->dialogURL("NEW COMMUNICATION",$this->api->url($vp->getURL(),['contact_id'=>$this->contact_id])))->_selector('.create');

		$this->js('click',$this->js()->univ()->alert("Send All As Pdf"))->_selector('.inform');	
		
		// $lister->on('click','.duplicate-btn',function($js,$data)use($vp,$vp_url){
		// 	return $js->univ()->frameURL('Duplicate',$this->app->url($vp_url,['template_id'=>$data['id']]));
		// });
	}

	function formatRow(){
		$to_mail = json_decode($this->model['to_raw'],true);
		$to_lister = $this->app->add('CompleteLister',null,null,['view/communication1','to_lister']);
		$to_lister->setSource($to_mail);
			
		$cc_raw = json_decode($this->model['cc_raw'],true);
		$cc_lister = $this->app->add('CompleteLister',null,null,['view/communication1','cc_lister']);
		$cc_lister->setSource($cc_raw);

		$from_mail = json_decode($this->model['from_raw'],true);
		$from_lister = $this->app->add('CompleteLister',null,null,['view/communication1','from_lister']);
		$from_lister->setSource($from_mail);

		$attach=$this->app->add('CompleteLister',null,null,['view/communication1','Attachments']);
		$attach->setModel('xepan\communication\Communication_Attachment')->addCondition('communication_email_id',$this->model->id);

		$this->current_row_html['description'] = $this->current_row['description'];
		
		if($this->model['attachment_count'])
			$this->current_row_html['attachment'] = '<span><i style="color:green" class="fa fa-paperclip"></i></span>';
		else
			$this->current_row_html['attachment']='';

		$this->current_row_html['to_lister'] = $to_lister->getHtml();
		$this->current_row_html['cc_lister'] = $cc_lister->getHtml();
		$this->current_row_html['from_lister'] = $from_lister->getHtml();
		$this->current_row_html['Attachments'] = $attach->getHtml();
		return parent::formatRow();
	}

	function defaultTemplate(){
		return['view\communication1'];
	}
}