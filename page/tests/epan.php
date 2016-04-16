<?php
namespace xepan\base;



class page_tests_epan extends Page_Tester {
    public $title = 'Epan Related Basic Tests';

     public $proper_responses=[
        "Test_defaultEpan"=>'web'
        ];

     function test_defaultEpan(){
     	return $this->add('xepan\base\Model_Epan')->setOrder('id')->tryLoadANy()->get('name');
     }
}
?>