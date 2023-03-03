<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['label'=>'Nom de la sortie :', 'attr'=>['class'=>'form-control']])
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :', 'attr'=>['class'=>'form-control'] ])

            ->add('dateHeureDebut', DateTimeType::class, ['label' => 'Date et heure de la sortie :',
                'widget' => 'single_text',
                'attr' => [ 'class' => 'js-datetimepicker form-control']
                ])
            ->add('villes', EntityType::class, ['mapped' => false,'class'=> Ville::class, 'choice_label'=> 'nom', 'label'=> 'Ville :', 'attr'=>['class'=>'form-control'] ])
            ->add('lieu', EntityType::class, ['class'=> Lieu::class, 'choice_label'=> 'nom', 'label'=> 'Lieu :', 'attr'=>['class'=>'form-control']])

            ->add('dateLimiteInscription', DateType::class, ['label' => 'Date limite d\'inscription :',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker form-control'],
                ])


            ->add('nbInscriptionsMax', IntegerType::class,['label'=> 'Nombre de places :', 'attr'=>['class'=>'form-control']])


            ->add('duree', IntegerType::class, ['label'=> 'Durée', 'attr'=>['class'=>'form-control']])
            ->add('latitude',TextType::class, ['mapped' => false, 'label' => 'Latitude :', 'attr'=>['class'=>'form-control']])

            ->add('infosSortie', TextareaType::class, ['label' => 'Description et infos', 'attr'=>['class'=>'form-control']])
            ->add('longitude',TextType::class, ['mapped' => false, 'label' => 'Longitude :', 'attr'=>['class'=>'form-control']])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer', 'attr'=>['class'=>'btn btn-primary']])
            ->add('publier', SubmitType::class, ['label' => 'Publier la sortie', 'attr'=>['class'=>'btn btn-primary']])
            ->add('supprimer', SubmitType::class, ['label' => 'Supprimer sortie', 'attr'=>['class'=>'btn btn-danger']])
             ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'allow_extra_fields' => true
        ]);
    }
}
