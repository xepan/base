<?php


namespace xepan\base;

class Controller_PointEventManager extends \AbstractController {
	
	function handleEvent($app,$event_name,$param){
		// throw new \Exception(var_dump($param), 1);
		switch ($event_name) {
			case 'telemarketing_response':
				$m=$this->add('xepan\base\Model_PointSystem');
				$m['contact_id']=$param['lead']['id'];
				$m['score']=$m['score']+10;
				$m->save();
				break;
			case 'landing_response':
				// throw new \Exception(var_dump($param['response']['content_id']), 1);
				// exit;
				$m=$this->add('xepan\base\Model_PointSystem');
				$m['contact_id']=$param['lead']['id'];
				$m['score']=$m['score']+30;
				$m['landing_content_id']=$param['response']['content_id'];
				$m['landing_campaign_id']=$param['response']['campaign_id'];
				$m->save();
			
				break;
			default:
				# code...
				break;
		}
	}
}