<?php
namespace xepan\base;

class Form_Field_Plus extends \autocomplete\Form_Field_Basic
{
    public $fields=null;

    function setModel($model)
    {
        parent::setModel($model);
        $self = $this;
        $fields = $this->fields;

        $f = $this->other_field;

        // Add buttonset to name field
        $bs = $f->afterField()->add('ButtonSet');

        // Add button - open dialog for adding new element
        $bs->add('Button')
            ->set('+')
            ->add('VirtualPage')
            ->bindEvent('Add New Record', 'click')
                ->set(function($page)use($self,$fields) {
                    $form = $page->add('Form_Stacked');
                    $form->setModel($self->model,$fields);
                    $form->addSubmit('Add And Select');
                    if ($form->isSubmitted()) {
                        $form->update();
                        $js = array();
                        $js[] = $self->js()->val($form->model[$self->id_field]);
                        $js[] = $self->other_field->js()->val($form->model[$self->title_field]);
                        $form->js(null, $js)->univ()->closeDialog()->execute();
                    }
                });
    }
}
