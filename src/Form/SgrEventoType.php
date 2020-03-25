<?php

namespace App\Form;

use App\Entity\SgrEvento;
use App\Entity\SgrGrupoAsignatura;
use App\Form\DataTransformer\DateTimeTransformer;
use App\Form\DataTransformer\TimeTransformer;
//use App\Form\DataTransformer\GrupoAsignaturaTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\Length;

class SgrEventoType extends AbstractType
{

    private $transformerDateTime;
    private $transformerTime;
    //private $transformerGrupoAsignaturas;

    public function __construct(DateTimeTransformer $transformerDateTime,TimeTransformer $transformerTime)
    {
        $this->transformerDateTime = $transformerDateTime;
        $this->transformerTime = $transformerTime;
        //$this->transformerGrupoAsignaturas = $transformerGrupoAsignaturas;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //Requeridos
            ->add('titulo')
            ->add('actividad')
            ->add('espacio',null,['required' => true])
            //Calendario
            ->add('f_inicio', TextType::class, array(
                'required' => true,
                'label' => 'Fecha inicio',
                'invalid_message' => 'Esto no es una fecha válida',
                //'constraints' => [new Lenght(['min' => 3])],
                'translation_domain' => 'App',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'dd/mm/yyyy',
                ),
            ))
            ->add('f_fin', TextType::class, array(
                'required' => false,
                'label' => 'Hasta',
                'translation_domain' => 'App',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'dd/mm/yyyy',
                ),
            ))

            //->add('h_inicio',null,['label' => 'De'])
            ->add('h_inicio', TextType::class, array(
                'required' => true,
                'label' => 'Hora inicio',
                'translation_domain' => 'App',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'H:i',
                ),
            ))
            //->add('h_fin',null,['label' => 'Hasta'])
            ->add('h_fin', TextType::class, array(
                'required' => true,
                'label' => 'Hora fin',
                'translation_domain' => 'App',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'H:i',
                ),
            ))
            ->add('dias', ChoiceType::class, [
                            'choices' => [
                                            'Lunes' => 1,
                                            'Martes' => 2,
                                            'Miércoles' => 3,
                                            'Jueves' => 4,
                                            'Viernes' => 5,
                                        ],
                            'expanded'  => true,
                            'multiple'  => true,
                            'choice_attr' => function($choice, $key, $value) {
                                // adds a class like attending_yes, attending_no, etc
                                return ['class' => 'tinymce'];
                            },
                            'attr' => ['class' => 'form-check-inline'],
                            'label' => 'Los días: ',
            ])
            //Opcionales
            ->add('titulacion')
            ->add('asignatura')
            ->add('grupoAsignatura', null ,[
                            'label' => 'Grupo',
                            'required' => false,
                            'choice_label' => 'nombre',
                            'expanded' => false,
                            'multiple' => false,
                             ])
            ->add('profesor')
            ->add('submit', SubmitType::class, [ 'label' => 'Salvar' ])

            

            //->add('estado')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('user')
            
        ;

        $builder->get('f_inicio')
            ->addModelTransformer($this->transformerDateTime);
        $builder->get('f_fin')
            ->addModelTransformer($this->transformerDateTime);
        $builder->get('h_inicio')
            ->addModelTransformer($this->transformerTime);
        $builder->get('h_fin')
            ->addModelTransformer($this->transformerTime);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SgrEvento::class,
        ]);
    }
}
