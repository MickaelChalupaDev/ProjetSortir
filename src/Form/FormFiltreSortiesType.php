<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFiltreSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, ['class'=> Campus::class, 'choice_label'=> 'nom', 'label'=> 'Campus :', 'attr'=> ['class'=> 'form-select'] ])
            ->add('nom', TextType::class, ['mapped' => false, 'label' => 'Le nom de la sortie contient :','required' => false, 'attr'=> ['class'=> 'form-control']])
            ->add('entre', DateTimeType::class, ['mapped' => false,'label' => 'Entre :',
                'widget' => 'single_text',
                'attr' => [ 'class' => 'js-datetimepicker form-control', 'style' => 'width: 600px;'],
                'required' => false,
            ])
            ->add('et', DateType::class, ['mapped' => false,'label' => 'Et :',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker form-control'],
                'required' => false,
            ])
//            ->add('organisateur', )

            ->add('S0', CheckboxType::class, ['mapped' => false,
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,'attr'=> ['class'=> 'form-check-input']
            ])
            ->add('S1', CheckboxType::class, ['mapped' => false,
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,'attr'=> ['class'=> 'form-check-input']
            ])
            ->add('S2', CheckboxType::class, ['mapped' => false,
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,'attr'=> ['class'=> 'form-check-input']
            ])
            ->add('S3', CheckboxType::class, ['mapped' => false,
                'label'    => 'Sorties passées',
                'required' => false,'attr'=> ['class'=> 'form-check-input']
            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Rechercher'
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>Sortie::class,
            'allow_extra_fields' => true
        ]);
    }
}
