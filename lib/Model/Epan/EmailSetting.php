<?php

/**
* description: epan may have many Email Settings for sending and receiving enails.
* Since xEpan is primarily for cloud multiuser SaaS. Email settings are considered as base
* and included in Epan, not in any top layer Application.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Epan_EmailSetting extends \Model_Table{

	public $table='emailsetting';
	public $title_field='email_id';

	function init(){
		parent::init();
		// TODO : add all required fields for email + can_use_in_mass_emails
		$this->addField('email_transport')->setValueList(array('SmtpTransport'=>'SMTP Transport','SendmailTransport'=>'SendMail','MailTransport'=>'PHP Mail function'))->defaultValue('smtp');

		$this->addField('encryption')->enum(array('none','ssl','tls'))->mandatory(true);
		$this->addField('email_host');
		$this->addField('email_port');
		$this->addField('email_username');
		$this->addField('email_password')->type('password');
		$f=$this->addField('email_reply_to');
		$f=$this->addField('email_reply_to_name');

		$this->addField('from_email');
		$this->addField('from_name');
		$this->addField('sender_email');
		$this->addField('sender_name');
		
		$this->addField('smtp_auto_reconnect')->type('int')->hint('Auto Reconnect by n number of emails');
		$this->addField('email_threshold')->type('int')->hint('Threshold To send emails with this Email Configuration PER MINUTE');

		$this->addField('emails_in_BCC')->type('int')->hint('Emails to be sent by bunch of Bcc emails, to will be used same as From, 0 to send each email in to field')->defaultValue(0);
		$this->addField('last_emailed_at')->type('datetime')->system(true);
		$this->addField('email_sent_in_this_minute')->type('int')->system(true);

		$this->addField('bounce_imap_email_host')->caption('Host');
		$this->addField('bounce_imap_email_port')->caption('Port');
		$this->addField('return_path')->Caption('Username / Email');
		
		$this->addField('bounce_imap_email_password')->type('password')->caption('Password');
		$this->addField('bounce_imap_flags')->mandatory(true)->defaultValue('/imap/ssl/novalidate-cert')->caption('Flags');
		
	}
}
