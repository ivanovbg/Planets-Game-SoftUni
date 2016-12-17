<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
                'label'=>"Email"
            ]);
        $builder->add('password', PasswordType::class, [
                'label'=>'Текущата Ви парола'
            ]);
        $builder->add('new_password', PasswordType::class,[
            'label'=>'Нова парола: ',
            'required' =>false

        ]);
        $builder->add('full_name', TextType::class,
            [
                'label'=>"Вашето име:"
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['user_class'=>User::class]);

    }

    public function getName()
    {
        return 'app_bundle_edit_user_profile';
    }
}
