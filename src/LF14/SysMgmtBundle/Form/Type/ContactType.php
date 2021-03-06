<?php

namespace LF14\SysMgmtBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'LF14\SysMgmtBundle\Model\Contact',
        'name'       => 'contact',
    );

    /**
     *{@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('email');
        $builder->add('address');
        $builder->add('phone1');
        $builder->add('phone2');
//         $builder->add('createdAt');
//         $builder->add('updatedAt');
        $builder->add('save', 'submit', array(
				'icon' => 'save',
				'attr' => array(
						'class' => 'btn btn-primary pull-right')
				)
		);
		$builder->add('cancel', 'submit', array(
				'validation_groups' => false,
				'attr' => array(
						'value' => 'cancel',
						'formnovalidate' => 'formnovalidate',
						'class' => 'btn btn-default pull-right btn-margin-right')
				)
		);		
    }
}
