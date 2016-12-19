<?php
/***********************************************************

Quicksearch represents one-field filter which works perfectly with a grid

Reference:
http://agiletoolkit.org/doc/ref

==ATK4===================================================
This file is part of Agile Toolkit 4
http://agiletoolkit.org/

(c) 2008-2013 Agile Toolkit Limited <info@agiletoolkit.org>
Distributed under Affero General Public License v3 and
commercial license.

See LICENSE or LICENSE_COM for more information
=====================================================ATK4=*/

namespace xepan\base;

class QuickSearch extends \Filter
{

    // icons
    public $submit_icon = 'search icon';
    public $cancel_icon = 'cancel icon';

    // field
    public $search_field;

    // buttonset
    public $bset_class = 'ui cuttons';
    public $bset_position = 'after'; // after|before
    protected $bset;

    // cancel button
    public $show_cancel = true; // show cancel button? (true|false)

    /**
     * Initialization
     *
     * @return void
     */
    function init()
    {
        parent::init();

        // template fixes
        // $this->addClass('ui form');
        $this->template->trySet('fieldset', 'atk-row');
        $this->template->tryDel('button_row');

        $this->addClass('atk-col-3');

        // add field
        $this->search_field = $this->addField('Line', 'q', '')->setAttr('placeholder','Search')->setNoSave();

        // cancel button
        if($this->show_cancel && $this->recall($this->search_field->short_name)) {
            $this->add('View',null,'cancel_button')
                ->setClass('atk-cell')
                ->add('HtmlElement')
                ->setElement('A')
	            ->setAttr('href','javascript:void(0)')
                ->setClass('ui middle aligned very compact icon button')
                ->setHtml('<i class="red cancel icon"></i>')
                ->js('click', array(
                    $this->search_field->js()->val(null),
                    $this->js()->submit()
                ));
        }

        // search button
        $this->add('HtmlElement',null,'form_buttons')
            ->setElement('A')
	        ->setAttr('href','javascript:void(0)')
            ->setClass('ui very compact middle aligned icon button')
            ->setHtml('<i class="search icon"></i>')
            ->js('click', $this->js()->submit());
    }

    /**
     * Set fields on which filtering will be done
     *
     * @param string|array $fields
     * @return QuickSearch $this
     */
    function useFields($fields)
    {
        if(is_string($fields)) {
            $fields = explode(',', $fields);
        }
        $this->fields = $fields;
        return $this;
    }

    /**
     * Process received filtering parameters after init phase
     *
     * @return void
     */
    function postInit()
    {
        parent::postInit();
        if(!($v = trim($this->get('q')))) {
            return;
        }

        if($this->view->model->hasMethod('addConditionLike')){
            return $this->view->model->addConditionLike($v, $this->fields);
        }
        if($this->view->model) {
            $q = $this->view->model->_dsql();
        } else {
            $q = $this->view->dq;
        }
        $or = $q->orExpr();
        foreach($this->fields as $field) {
            $or->where($field, 'like', '%'.$v.'%');
        }
        $q->having($or);
    }
    function defaultTemplate(){
        return array('form/quicksearch');
    }
}
