<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyCustomDateType extends DateTimeType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        // Set the defaults from the DateTimeType we're extending from
        parent::configureOptions($resolver);

        // Override: Go back 20 years and add 20 years
        $resolver->setDefault('years', range(date('Y') - 70, date('Y') + 0));


    }
}