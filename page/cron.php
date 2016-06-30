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

class page_cron extends \Page {
	public $title='Cron Job Collector and Executor';

	function init(){
		parent::init();
		
		// // Job 1
		// $job1 = new \Cron\Job\ShellJob();
		// $job1->setCommand('ls -la /path/to/folder');
		// $job1->setSchedule(new \Cron\Schedule\CrontabSchedule('*/5 * * * *'));

		// // Job 2 : Remove folder contents every hour.
		// $job2 = new \Cron\Job\ShellJob();
		// $job2->setCommand('rm -rf /path/to/folder/*');
		// $job2->setSchedule(new \Cron\Schedule\CrontabSchedule('0 0 * * *'));

		$resolver = new \Cron\Resolver\ArrayResolver();
		// $resolver->addJob($job1);
		// $resolver->addJob($job2);

		$this->app->hook('cron_exector',[$resolver]);

		$cron = new \Cron\Cron();
		$cron->setExecutor(new \Cron\Executor\Executor());
		$cron->setResolver($resolver);

		var_dump($cron->run());

	}
}
