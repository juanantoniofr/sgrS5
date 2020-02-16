<?php

namespace App\Form;

use App\Entity\SgrEvento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SgrEventoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            //->add('estado')
            ->add('periodica')
            ->add('f_inicio')
            ->add('f_fin')
            ->add('h_inicio')
            ->add('h_fin')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('user')
            ->add('espacio')
            ->add('asignatura')
            ->add('profesor')
            ->add('grupoAsignatura')
            ->add('titulacion')
            ->add('actividad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrEvento::class,
        ]);
    }
}
