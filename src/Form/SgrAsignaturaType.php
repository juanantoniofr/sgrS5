<?php

namespace App\Form;

use App\Entity\SgrAsignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SgrAsignaturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo',null,['label'=>'Código'])
            ->add('nombre')
            ->add('cuatrimestre')
            ->add('curso')
            ->add('sgrTitulacion',null,['label'=>'Titulación'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrAsignatura::class,
        ]);
    }
}
