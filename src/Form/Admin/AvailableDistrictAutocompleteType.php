<?php

namespace App\Form\Admin;

use App\Admin\DistrictAdmin;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailableDistrictAutocompleteType extends AbstractType
{
    public function getParent()
    {
        return ModelAutocompleteType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'quiet_millis' => 500,
                'placeholder' => '...',
                'property' => 'name',
                'callback' => [DistrictAdmin::class, 'prepareAutocompleteFilterCallback'],
            ]
        );
    }
}
