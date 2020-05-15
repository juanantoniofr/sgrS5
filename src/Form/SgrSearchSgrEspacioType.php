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
use Symfony\Component\Validator\Constraints\Length;

use App\Entity\SgrEquipamiento;
use App\Entity\SgrTermino;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Date;

//use Symfony\Component\Validator\Constraints\Date;
use App\Entity\SgrAsignatura; //??
use App\Entity\SgrProfesor; //??
use App\Entity\SgrEspacio; //??
use App\Entity\SgrTipoActividad; //??

class SgrSearchSgrEspacioType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aforoExamen', TextType::class, [
                                    'label' => 'aforo examen',
                                    'required' => false,
                                    'attr' => array('placeholder' => 'Indique aforo para exámenes'),
                                ])
            ->add('aforo', TextType::class, [
                                    'label' => 'aforo',
                                    'required' => false,
                                    'attr' => array('placeholder' => 'Indique aforo máximo'),
                                ])
            ->add('equipamiento', EntityType::class,[
                                    'label' => 'Equipamiento',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Equipamiento',
                                    // looks for choices from this entity
                                    'class' => SgrEquipamiento::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                    'expanded' => true,
                                    'multiple' => true,
                                ])
            ->add('termino', EntityType::class,[
                                    'label' => 'Término Taxonomía',
                                    'required' => false,
                                    'placeholder' => 'Seleccione término',
                                    // looks for choices from this entity
                                    'class' => SgrTermino::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                ])            
            ->add('f_inicio', TextType::class, array(
                                    'required' => true,
                                    'label' => 'Fecha inicio',
                                    'invalid_message' => 'Esto no es una fecha válida',
                                    //'constraints' => [new Lenght(['min' => 3])],
                                    'translation_domain' => 'App',
                                    'attr' => array(
                                        'class' => 'form-control input-inline datetimepicker',
                                        'data-provide' => 'datepicker',
                                        'data-format' => 'hh:mm',
                                        
                                    ),
            ))
            ->add('f_fin', TextType::class, [
                                    'required' => true,
                                    'label' => 'Hasta',
                                    'translation_domain' => 'App',
                                    'attr' => [
                                        'class' => 'form-control input-inline datetimepicker',
                                        'data-provide' => 'datepicker',
                                        'data-format' => 'hh:mm',
                                    ],
                                    'constraints' => [  new NotBlank(),
                                                        new GreaterThanOrEqual( ['propertyPath' => 'parent.all[f_inicio].data' , 'message' => "Debe ser igual o mayor que fecha inicio"] ), 
                                    ],
            ])
            ->add('h_inicio', TextType::class, array(
                    'required' => true,
                    'label' => 'Hora inicio',
                    'translation_domain' => 'App',
                    'attr' => array(
                        'class' => 'form-control input-inline datetimepicker',
                        'data-provide' => 'datepicker',
                        'data-format' => 'HH-mm',
                    ),
            ))
            ->add('h_fin', TextType::class, array(
                    'required' => true,
                    'label' => 'Hora fin',
                    'translation_domain' => 'App',
                    'attr' => array(
                        'class' => 'form-control input-inline datetimepicker',
                        'data-provide' => 'datepicker',
                        'data-format' => 'HH:mm',
                    ),
                ))
        ;

   
    }

}