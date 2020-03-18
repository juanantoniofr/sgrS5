<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Date;

use App\Form\DataTransformer\DateTimeTransformer;
use App\Form\DataTransformer\TimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Entity\SgrTaxonomia;
use App\Entity\SgrTermino;
use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;
use App\Entity\SgrEspacio;
use App\Entity\SgrTipoActividad;

class SgrFiltersSgrEventosType extends AbstractType
{
    private $transformerDateTime;
    private $transformerTime;

    public function __construct(DateTimeTransformer $transformerDateTime,TimeTransformer $transformerTime)
    {
        $this->transformerDateTime = $transformerDateTime;
        $this->transformerTime = $transformerTime;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termino', EntityType::class, [
                                    'label' => 'Categoría',
                                    'required' => false,
                                    'placeholder' => 'Todo o Seleccione Categoría',
                                    'class' => SgrTermino::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('taxonomia', EntityType::class, [
                                    'label' => 'Taxonomia',
                                    'required' => false,
                                    'placeholder' => 'Seleccione categoría',
                                    'class' => SgrTaxonomia::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('titulacion', EntityType::class, [
                                    'label' => 'Titulación',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Titulación',
                                    'class' => SgrTitulacion::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('curso', ChoiceType::class, [
                                    'label' => 'Curso',
                                    'placeholder' => 'Seleccione Curso',
                                    'required' => false,
                                    'choices'  => [
                                        'Primero' => 1,
                                        'Segundo' => 2,
                                        'Tercero' => 3,
                                        'Cuarto' => 4,]
                                    ])
            ->add('asignatura', EntityType::class, [
                                    'label' => 'Asignatura',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Asignatura',
                                    'class' => SgrAsignatura::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('profesor', EntityType::class,[
                                    'label' => 'Profesor',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Profesor',
                                    'class' => SgrProfesor::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('f_inicio', TextType::class, array(
                                    'required' => true,
                                    'label' => 'Desde',
                                    'constraints' => [ new NotBlank(), ],
            ))
            ->add('f_fin', TextType::class, array(
                                    'required' => true,
                                    'label' => 'Hasta',
                                    'constraints' => [  new NotBlank(),
                                                        new GreaterThanOrEqual( ['propertyPath' => 'parent.all[f_inicio].data' , 'message' => "Debe ser igual o menor que fecha desde"] ), 
                                                        ],
            ))
            ->add('espacio', EntityType::class,[
                                    'label' => 'Espacio',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Espacio',
                                    'class' => SgrEspacio::class,
                                    'choice_label' => 'nombre',
                                    'expanded' => true,
                                    'multiple' => true,
                                    'attr' => ['class' => 'form-check-inline'],
                                ])
            ->add('actividad', EntityType::class,[
                                    'label' => 'Actividad',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Actividad',
                                    'class' => SgrTipoActividad::class,
                                    'choice_label' => 'actividad',
                                ])
        ;

        $builder->get('f_inicio')
            ->addModelTransformer($this->transformerDateTime);
        $builder->get('f_fin')
            ->addModelTransformer($this->transformerDateTime);

   
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

}