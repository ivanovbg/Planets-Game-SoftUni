<?php

namespace AppBundle\Form;

use AppBundle\Entity\Messages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendMessage extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('receiver', TextType::class, [
            'label'=>"Получател: "
        ]);
        $builder->add('text', TextareaType::class, [
            'label'=>"Съобщение: ",
            'attr' => array('rows' => '20', 'cols'=>'70')
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['message_class'=>Messages::class]);
    }

    public function getName()
    {
        return 'app_bundle_send_message';
    }
}
