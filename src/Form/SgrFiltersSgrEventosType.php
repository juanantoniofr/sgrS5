<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;

class SgrFiltersSgrEventosType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulacion', EntityType::class, [
                                    'label' => 'Titulación',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Titulación',
                                    // looks for choices from this entity
                                    'class' => SgrTitulacion::class,
                                    // uses the User.username property as the visible option string
                                    'choice_label' => 'nombre',
                                ])
            ->add('curso', ChoiceType::class, [
                                'label' => 'Curso',
                                'placeholder' => 'Seleccione Curso',
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
        ;

   
    }

}