<?php

/**
* description: xEpan Menu Bar
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Menu_Bar extends \Menu_Advanced{

	public function addMenu($title, $class=null, $options=array())
    {
        $m = $this->add('xepan\base\Menu_Bar',null,'SubMenu',['menu/menu']);
        $m->set($title);
        return $m;
    }

    public function addItem($title, $action=null){
    	$i = $this->add('xepan\base\Menu_Bar',null,'SubMenu',['menu/item']);

        if (is_array($title)) {

            if ($title['badge']) {
                $i->add('View',null,'Badge')
                    ->setElement('span')
                    ->addClass('atk-label')
                    ->set($title['badge']);
                unset($title['badge']);
            }

        }

        if ($action) {
            if (is_string($action) || is_array($action) || $action instanceof URL) {
                $i->template->set('url',$url = $this->app->url($action));
                if($url->isCurrent($this->highlight_subpages)){
                    $i->addClass('active');
                }
            } else {
                $i->on('click',$action);
            }
        }

        $i->set($title);

        return $i;
    }

	function defaultTemplate() {
        return array('menu/bar');
    }
}
