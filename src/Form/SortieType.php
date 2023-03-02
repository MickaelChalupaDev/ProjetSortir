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
            ->add('nom', TextType::class,['label'=>'Nom de la sortie :'])
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :' ])

            ->add('dateHeureDebut', DateTimeType::class, ['label' => 'Date et heure de la sortie :',
                'widget' => 'single_text',
                'attr' => [ 'class' => 'js-datetimepicker']
                ])
            ->add('villes', EntityType::class, ['mapped' => false,'class'=> Ville::class, 'choice_label'=> 'nom', 'label'=> 'Ville :' ])
            ->add('lieu', EntityType::class, ['class'=> Lieu::class, 'choice_label'=> 'nom', 'label'=> 'Lieu :'])

            ->add('dateLimiteInscription', DateType::class, ['label' => 'Date limite d\'inscription :',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                ])


            ->add('nbInscriptionsMax', IntegerType::class,['label'=> 'Nombre de places :'])


            ->add('duree', IntegerType::class, ['label'=> 'Durée'])
            ->add('latitude',TextType::class, ['mapped' => false, 'label' => 'Latitude :'])

            ->add('infosSortie', TextareaType::class, ['label' => 'Description et infos'])
            ->add('longitude',TextType::class, ['mapped' => false, 'label' => 'Longitude :'])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'Publier la sortie'])
            ->add('supprimer', SubmitType::class, ['label' => 'Supprimer la sortie'])
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
