<?php

namespace App\Form;

use App\Entity\SgrGrupoAsignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SgrGrupoAsignaturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('capacidad')
            ->add('sgrAsignatura')
            ->add('sgrProfesors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrGrupoAsignatura::class,
        ]);
    }
}
