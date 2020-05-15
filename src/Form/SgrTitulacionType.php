<?php

namespace App\Form;

use App\Entity\SgrTitulacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SgrTitulacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo')
            ->add('nombre')
            ->add('tipo', ChoiceType::class, [
                    'choices'  => [
                        'Grado' => 'Grado',
                        'Doble Grado' => 'Doble Grado',
                        'Máster' => 'Máster',
                    ],
                ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrTitulacion::class,
        ]);
    }
}
