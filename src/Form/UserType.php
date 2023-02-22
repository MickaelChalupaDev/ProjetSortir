<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class,['label'=>'Prénom :'])
            ->add('nom', TextType::class,['label'=>'Nom :'])
            ->add('telephone', IntegerType::class,['label'=>'Téléphone :'])
            ->add('email', EmailType::class,['label'=>'Email :'])
            ->add('password', TextType::class,['label'=>'Mot de passe :',
                'attr' => ['class' => 'hidden-field', 'value' => '*****']
                 ])
            ->add('confirmation', TextType::class,['label'=>'Confirmation :','mapped'=> false ])
            //->add('password', TextType::class,['label'=>'Confirmation :'])
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :' ])
            //->add('photo')
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true
        ]);
    }
}
