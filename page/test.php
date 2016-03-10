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

class page_test extends \Page {
	public $title='Page Title';

	function init(){
		parent::init();
		// $email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')
		// 						->addCondition('imap_email_password','not',null);

		// foreach ($email_settings as $email_setting) {
		// 	$cont = $this->add('xepan\communication\Controller_ReadEmail',['email_setting'=>$email_setting]);
		// 	$mbs = $cont->getMailBoxes();
		// 	foreach ($mbs as $mb) {
		// 		var_dump($cont->fetch($mb));
		// 	}
		// }

		$email='{"id":"","parent":null,"topic":"(no subject)","mailbox":"INBOX","uid":"4120","date":false,"subject":"(no subject)","from":{"name":null,"email":"care@yet5.com"},"flags":["seen"],"to":[{"name":null,"email":"info@xavoc.com"}],"cc":[{"name":null,"email":"yetfive20140909@gmail.com"}],"bcc":[],"attachment":[],"body":{"text\/html":" Content preview: Hi, Greetings from Yet5.com - India\'s No.1 Exclusive website\r\n for Training Industry. We have received a new training enquiry from Rajasthan\r\n for Linux, the summary as below. Kindly login to IMS - Institute Management\r\n System (http:\/\/www.yet5.com\/edu) with your login credentials to view mobile\r\n number and email id of the enquiry Name : Gourav City : Rajasthan Area :\r\n Alwar Contact No.: 787xxxx905 - [Mobile Number Verified] Course Interested\r\n in: Linux Email : Khaxxxxxxlg27@gmail.com Comments: Best it training institution\r\n : () You are reciving Yet5.com\'s training enquiries Randomly. We have recieved\r\n 13 enquiries in last 3 months (Since 09-Dec-2015) for your 5 courses. Please\r\n call us at +91 9514 400400 \/ 9514 900900 for custom proposal and Instant\r\n activation. Thanks & Regards, Team Yet5.com Email: marketing@yet5.com www.yet5.com\r\n [...] \r\n \r\n Content analysis details: (6.6 points, 5.0 required)\r\n \r\n pts rule name description\r\n ---- ---------------------- --------------------------------------------------\r\n 0.0 RCVD_IN_DNSWL_BLOCKED RBL: ADMINISTRATOR NOTICE: The query to DNSWL\r\n was blocked. See\r\n http:\/\/wiki.apache.org\/spamassassin\/DnsBlocklists#dnsbl-block\r\n for more information.\r\n [198.57.196.108 listed in list.dnswl.org]\r\n 0.0 HTML_MESSAGE BODY: HTML included in message\r\n 1.1 MIME_HTML_ONLY BODY: Message only has text\/html MIME parts\r\n -0.1 DKIM_VALID_AU Message has a valid DKIM or DK signature from author\'s\r\n domain\r\n -0.1 DKIM_VALID Message has at least one valid DKIM or DK signature\r\n 0.1 DKIM_SIGNED Message has a DKIM or DK signature, not necessarily valid\r\n 1.6 MISSING_MID Missing Message-Id: header\r\n 2.0 RDNS_NONE Delivered to internal network by a host with no rDNS\r\n 0.6 HTML_MIME_NO_HTML_TAG HTML-only message, but there is no HTML tag\r\n 1.4 MISSING_DATE Missing Date: header\r\n 0.0 T_FILL_THIS_FORM_SHORT Fill in a short form with personal information\r\nX-Spam-Flag: YES\r\nSubject: ***SPAM*** Yet5.com : New Training Enquiry for Linux\r\n\r"}}';
		// echo "<pre>";
		$email_array = json_decode($email,true);

		// print_r($email_array);
		// exit;
		$btn=$this->add('Button')->set('Save Emails');
		$btn->onClick(function()use($email_array){
			$email_model=$this->add('xepan\communication\Model_Communication');	
			$email_model['uid']=$email_array['uid'];
			$email_model['from_raw']=json_encode($email_array['from']);
			$email_model['to_raw']=json_encode($email_array['to']);
			$email_model['cc_raw']=json_encode($email_array['cc']);
			$email_model['bcc_raw']=json_encode($email_array['bcc']);
			$email_model['flags']=json_encode($email_array['flags']);
			// $email_model['bcc_raw']=json_encode($email_array['attachment']);
			$email_model['title']=$email_array['subject'];
			$email_model['description']=json_encode($email_array['body']);
			$email_model->save();
			return $this->js()->reload();
		});

	}
}
