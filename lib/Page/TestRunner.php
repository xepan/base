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


class Page_TestRunner extends \Page {
	
	public $title='xEpan Base Tests';
	public $dir='tests';

	function init(){
		parent::init();
		
        $dir = $this->dir;
        $m=$this->add('xepan\base\Model_xEpantester',array('dir'=>$this->dir));

        $l = $this->add('Grid');
        $l->setModel($m);
        $l->addTotals()->setTotalsTitle('name', '%s test%s');
        
        $l->addHook('formatRow', function($l)use($dir){
            $n = $l->current_row['name'];
            $n = str_replace('.php', '', $n);
            $n = '<a href="'.$l->api->url(str_replace("\\","_",__NAMESPACE__).'/'.str_replace("/", "_", $dir).'/'.$n).'">'.$n.'</a>';
            $l->current_row_html['name'] = $n;
        });

	}
}
