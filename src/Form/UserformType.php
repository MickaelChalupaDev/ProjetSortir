<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotEqualTo;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class,['label'=>'Prénom :'])
            ->add('nom', TextType::class,['label'=>'Nom :'])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'user' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'ROLE_USER',
                'choice_attr' => [
                    'user' => ['value' => 'ROLE_USER'],
                    'admin' => ['value' => 'ROLE_ADMIN']
                ]
            ])
            ->add('telephone', IntegerType::class,['label'=>'Téléphone :'])
            ->add('email', EmailType::class,['label'=>'Email :'])
            ->add('password', PasswordType::class,['label'=>'Mot de passe :'])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmation :']])
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :' ])
            ->add('photo', FileType::class,['label'=>'Photo :','required'   => false])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])

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
