<?php

namespace App\Form;

use App\Entity\SgrAsignatura;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use App\Form\SgrGrupoAsignaturaType;
//use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SgrAsignaturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo',null,['label'=>'Código'])
            ->add('nombre')
            /*->add('grupos'), CollectionType::class, [
                        'entry_type' => SgrGrupoAsignatura::class,
                        'entry_options' => ['label' => true],
                     ])*/
            /*->add('grupo', ChoiceType::class, [
                                        'mapped' => false,
                                        'label' => 'Grupo',
                                        'choices'  => [
                                            'Seleccione grupo' => null,
                                            '1' => 1,
                                            '2' => 2,
                                            '3' => 3,
                                            '4' => 4,]
                                            ])*/        
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
