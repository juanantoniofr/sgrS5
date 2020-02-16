<?php

namespace App\Form;

use App\Entity\SgrEvento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SgrEventoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('actividad')
            
            ->add('titulacion')
            ->add('asignatura')
            ->add('grupoAsignatura')
            ->add('profesor')

            ->add('espacio')

            ->add('f_inicio',null,['label' => 'Desde'])
            ->add('f_fin',null,['label' => 'Hasta'])
            ->add('h_inicio',null,['label' => 'De'])
            ->add('h_fin',null,['label' => 'Hasta'])
            
            ->add('periodica',null,['label' => 'Repetir'])
            ->add('dias', ChoiceType::class, [
                            'choices' => [
                                            'Lunes' => '0',
                                            'Martes' => '1',
                                            'Miércoles' => '2',
                                            'Jueves' => '3',
                                            'Viernes' => '4',
                                        ],
                            'expanded'  => true,
                            'multiple'  => true,
                            'choice_attr' => function($choice, $key, $value) {
                                // adds a class like attending_yes, attending_no, etc
                                return ['class' => 'tinymce'];
                            },
                            'attr' => ['class' => 'form-check-inline'],
                            'label' => 'Días de la semana',
            ])

            //->add('estado')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('user')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrEvento::class,
        ]);
    }
}
