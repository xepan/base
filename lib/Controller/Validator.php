<?php



namespace xepan\base;

class Controller_Validator extends \Controller_Validator{
	
	function rule_unique($a,$field){

        $q = clone $this->owner->dsql();

        $result = $q
                ->where($field, $a)
                ->where($q->getField('id'),'<>', $this->owner->id)
                ->field($field)
                ->getOne();

        if($result !== null) return $this->fail('Value "{{arg1}}" already exists', $a);
    }

    function rule_unique_in_epan($a,$field){
        
        $q = clone $this->owner->dsql();

        $result = $q
                ->where($field, $a)
                ->where($q->getField('id'),'<>', $this->owner->id)
                ->where($q->expr('[0] = [1]',[$this->owner->getElement('epan_id'),$this->app->epan->id]))
                ->field($field)
                ->getOne();

        if($result !== null) return $this->fail('Value "{{arg1}}" already exists', $a);
    }

    function mb_str_len($str){
        return mb_strlen($str);
    }
}