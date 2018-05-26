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

class page_update extends \xepan\base\Page {
	public $title='xEpan Updator';

	function init(){
		parent::init();

		ini_set('memory_limit', '2048M');
		set_time_limit(0);

		$vp = $this->add('VirtualPage');
		$vp->set([$this,'updateZip']);

		$btn = $this->add('Button')->addClass('btn btn-primary btn-block')->set('UPDATE XEPAN AND ALL APPLICATIONS');
		$btn->js('click')->univ()->frameURL('UPDATE RUNNNING, DO NO CLOSE THIS WINDOW',$vp->getURL());
	}

	function updateGit($page){
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
				$output= shell_exec('git init 2>&1');
				$output= shell_exec('git remote remove origin 2>&1');
				$output= shell_exec('git remote add origin https://github.com/xepan/xepan2.git 2>&1');
				$output= shell_exec('git fetch --all 2>&1');
				$output.= shell_exec('git reset --hard origin/master 2>&1');
				// $output.= shell_exec('git pull origin master 2>&1');
				$c->out("output:<br/> <pre>$output</pre>");

				// update base app and call admin first
				chdir('vendor/xepan/base');
				$c->out('In Dir <b>'. getcwd() .'</b><br/>');
				$c->out('Pulling origin master <br/>');
				$output= shell_exec('git checkout origin master && git reset --hard origin/master && git pull origin master');
				$c->out("output:<br/> <pre>$output</pre>");
				
				chdir($root);

				$c->out('***** BASE DONE NOW RUNNING ADMIN WITH WGET ******');
				$c->out('wget -O /dev/null -o /dev/null "'.$this->app->url('/')->absolute().'"');
				$output = shell_exec('wget -O /dev/null -o /dev/null "'.$this->app->url('/')->absolute().'"');
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

	function updateZip($page){
		$page->add('View_Console')
		->set(function($c){
			
			chdir('..');
			$root = getcwd();

			if(file_exists('.git')){
				$c->err('Looks like it is managed by git repositories, update by git manually');
				$c->err('NOT UPDATED');
				return;
			}			

			$source = $this->app->getConfig('epan_base_path').'/xepan2.zip'; // THE FILE URL
			
			$c->out('Moved to '. $root);
			$c->out('Downloading zip from '.$source);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $source);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec ($ch);
			curl_close ($ch);
			// save as wordpress.zip
			$c->out('File downloaded, saving to local zip');
			
			$destination = "xepan2.zip"; // NEW FILE LOCATION
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);

			$c->out('File Saved, extracting zip now ...');

			$zip = new \xepan\base\zip;
			$res = $zip->extractZip('xepan2.zip','.'); // zip datei
			if ($res === TRUE) {
			    $c->out('Zip Extracted');
			    unlink('xepan2.zip');
			    // $c->out('xepan2.zip removed');
			} else {
			    $c->err('Unzip Error');
			}

		});
	}

	function checkIfCommandExists($cmd){
	    $prefix = strpos(strtolower(PHP_OS),'win') > -1 ? 'where' : 'which';
	    exec("{$prefix} {$cmd}", $output, $returnVal);
	    $returnVal !== 0;
	}
}
