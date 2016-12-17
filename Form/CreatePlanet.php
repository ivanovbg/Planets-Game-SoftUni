<?php

namespace AppBundle\Form;

use AppBundle\Entity\Planet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePlanet extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class,
            [
                'label'=>'Име на планетата: '
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['planet_class'=>Planet::class]);
    }

    public function getName()
    {
        return 'app_bundle_create_planet';
    }
}
