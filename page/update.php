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

class page_update extends \Page {
	public $title='xEpan Updator';

	function init(){
		parent::init();

		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		$vp = $this->add('VirtualPage');
		$vp->set([$this,'update']);

		$btn = $this->add('Button')->addClass('btn btn-primary btn-block')->set('UPDATE XEPAN AND ALL APPLICATIONS');
		$btn->js('click')->univ()->frameURL('UPDATE RUNNNING, DO NO CLOSE THIS WINDOW',$vp->getURL());
	}

	function update($page){
		$page->add('View_Console')
		->set(function($c){
			if($this->app->epan['name'] != "www"){
				$c->err('You are not authorised or you are already on hosted service and do not requires to update.');
				return;
			}
			try{

				chdir('..');
				
				$root = getcwd();

				$c->err('Root in start is '. $root);

				// update root
				$c->out('In Dir <b>'. getcwd() .'</b><br/>');
				$c->out('Pulling origin master <br/>');
				$output= shell_exec('git checkout origin master && git reset --hard origin/master && git pull origin master');
				$c->out("output:<br/> <pre>$output</pre>");

				// update base app and call admin first
				chdir('vendor/xepan/base');
				$c->out('In Dir <b>'. getcwd() .'</b><br/>');
				$c->out('Pulling origin master <br/>');
				$output= shell_exec('git checkout origin master && git reset --hard origin/master && git pull origin master');
				$c->out("output:<br/> <pre>$output</pre>");
				
				chdir($root);

				$c->out('***** BASE DONE NOW RUNNING ADMIN WITH WGET ******');
				$c->out('wget '.$this->app->url('/')->absolute());
				$output = shell_exec('wget '.$this->app->url('/')->absolute());
				$c->out("output:<br/> <pre>$output</pre>");

				$apps = array_column($this->add('xepan\base\Model_Epan_InstalledApplication')->getRows(),'application_namespace');
				foreach ($apps as $app_namespace) {
					$path="";
					if(file_exists('./vendor/'.str_replace("\\", "/", $app_namespace))){
						$path = './vendor/'.str_replace("\\", "/", $app_namespace);
					}
					elseif(file_exists('./shared/apps/'.str_replace("\\", "/", $app_namespace))) {
						$path = './shared/apps/'.str_replace("\\", "/", $app_namespace);
					}

					chdir($root);
					chdir($path);

					$c->out('In Dir <b>'. getcwd() .'</b><br/>');
					$c->out('Pulling origin master <br/>');
					$output= shell_exec('git checkout origin master && git reset --hard origin/master && git pull origin master');
					$c->out("output:<br/> <pre>$output</pre>");

					chdir($root);
				}

			}catch(\Exception $e){
				$c->err($path);
				$c->out($e->getMessage());
			}

		});
	}
}
