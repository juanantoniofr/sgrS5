<?php

namespace App\Form;

use App\Entity\SgrTipoActividad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;


class SgrTipoActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actividad')
            ->add('descripcion')
            ->add('color',null, array( 'attr' => array('class' => 'jscolor' )))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrTipoActividad::class,
        ]);
    }
}
