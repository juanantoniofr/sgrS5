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

use App\Entity\SgrTaxonomia;
use App\Entity\SgrTermino;
use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;
use App\Entity\SgrEspacio;
use App\Entity\SgrTipoActividad;

class SgrFiltersSgrEventosType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termino', EntityType::class, [
                                    'label' => 'Término',
                                    'required' => false,
                                    'placeholder' => 'Todo o Seleccione término',
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
                                    // looks for choices from this entity
                                    'class' => SgrAsignatura::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                ])
            ->add('profesor', EntityType::class,[
                                    'label' => 'Profesor',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Profesor',
                                    // looks for choices from this entity
                                    'class' => SgrProfesor::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                ])
            ->add('f_inicio', TextType::class, array(
                                    'required' => true,
                                    'label' => 'Desde',
                                    'data' => ( new \DateTime('01-09-2019') )->format('d/m/Y'),
                                    'constraints' => [  new NotBlank(),
                                                        new LessThanOrEqual([ 'propertyPath' => 'root["f_fin"]' ]), 
                                                        ],
            ))
            ->add('f_fin', TextType::class, array(
                                    'required' => true,
                                    'label' => 'Hasta',
                                    'data' => ( new \DateTime('31-08-2020') )->format('d/m/Y'),
                                    'constraints' => [  new NotBlank(),
                                                        new GreaterThanOrEqual([ 'propertyPath' => 'root["f_inicio"]' ]), 
                                                        ],
            ))
            ->add('espacio', EntityType::class,[
                                    'label' => 'Espacio',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Espacio',
                                    // looks for choices from this entity
                                    'class' => SgrEspacio::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                    'expanded' => true,
                                    'multiple' => true,
                                    'attr' => ['class' => 'form-check-inline'],
                                ])
            ->add('actividad', EntityType::class,[
                                    'label' => 'Actividad',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Actividad',
                                    // looks for choices from this entity
                                    'class' => SgrTipoActividad::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'actividad',
                                ])
        ;

   
    }

}