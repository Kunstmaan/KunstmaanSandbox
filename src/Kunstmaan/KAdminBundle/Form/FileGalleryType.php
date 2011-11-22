<?php

namespace Kunstmaan\KAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * class to define the form to upload a picture
 *
 */
class FileGalleryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('parent', 'entity', array( 'class' => 'Kunstmaan\KAdminBundle\Entity\FileGallery', 'required' => false ))
        ;
    }

    public function getName()
    {
        return 'kunstmaan_kadminbundle_filegallerytype';
    }
}

?>