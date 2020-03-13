<?php

namespace RichCongress\Bundle\UnitBundle\Tests\Resources\Form;

use RichCongress\Bundle\UnitBundle\Tests\Resources\Entity\DummyEntity;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DummyFormType extends FormType
{
    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'      => DummyEntity::class,
            'csrf_protection' => true,
            'csrf_token_id'   => 'dummy_form',
        ]);
    }
}
