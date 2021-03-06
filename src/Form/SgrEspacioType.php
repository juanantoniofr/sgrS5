<?php

namespace App\Form;

use App\Entity\SgrEspacio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SgrEspacioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            //->add('acl')
            ->add('aforo')
            ->add('aforo_examen')
            ->add('mediosDisponibles')
            ->add('termino')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrEspacio::class,
        ]);
    }
}
