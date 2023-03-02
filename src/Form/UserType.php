<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class,['label'=>'Prénom :', 'attr'=>['class'=>'form-control']])
            ->add('nom', TextType::class,['label'=>'Nom :', 'attr'=>['class'=>'form-control']])
            ->add('telephone', IntegerType::class,['label'=>'Téléphone :', 'attr'=>['class'=>'form-control']])
            ->add('email', EmailType::class,['label'=>'Email :', 'attr'=>['class'=>'form-control']])
            ->add('password', PasswordType::class,['label'=>'Mot de passe :', 'attr'=>['class'=>'form-control']])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :', 'attr'=>['class'=>'form-control']],
                'second_options' => ['label' => 'Confirmation :', 'attr'=>['class'=>'form-control']]])
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :', 'attr'=>['class'=>'form-control'] ])
            ->add('photo', FileType::class,['label'=>'Photo :','required'   => false, 'attr'=>['class'=>'form-control']])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer', 'attr'=>['class'=>'btn btn-primary']])

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
