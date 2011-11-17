<?php

namespace Kunstmaan\KAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * class to define the form to upload a picture
 *
 */
class TextPartType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array( 'required' => false, 'attr' => array( 'class' => 'rich_editor' )))
        ;
    }

    public function getName()
    {
        return 'kunstmaan_kadminbundle_textparttype';
    }
}

?>