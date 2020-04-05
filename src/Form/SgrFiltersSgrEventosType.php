<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\LessThanOrEqual;
//use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

use App\Form\DataTransformer\DateTimeTransformer;
use App\Form\DataTransformer\TimeTransformer;


use App\Entity\SgrTaxonomia;
use App\Entity\SgrTermino;
use App\Entity\SgrTitulacion;
use App\Entity\SgrAsignatura;
use App\Entity\SgrProfesor;
use App\Entity\SgrEspacio;
use App\Entity\SgrTipoActividad;
use App\Entity\SgrEvento;

class SgrFiltersSgrEventosType extends AbstractType
{
    private $transformerDateTime;
    private $transformerTime;

    private $entityManager;

    public function __construct(DateTimeTransformer $transformerDateTime,TimeTransformer $transformerTime, EntityManagerInterface $entityManager)
    {
        $this->transformerDateTime = $transformerDateTime;
        $this->transformerTime = $transformerTime;

        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('termino', EntityType::class, [
                                    'label' => 'Categoría',
                                    'required' => false,
                                    'placeholder' => 'Todo o Seleccione Categoría',
                                    'class' => SgrTermino::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('taxonomia', EntityType::class, [
                                    'label' => 'Taxonomia',
                                    'required' => false,
                                    'placeholder' => 'Todas o Seleccione categoría',
                                    'class' => SgrTaxonomia::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('titulacion', EntityType::class, [
                                    'label' => 'Titulación',
                                    'required' => false,
                                    'placeholder' => 'Todas o Seleccione Titulación',
                                    'class' => SgrTitulacion::class,
                                    'choice_label' => 'nombre',
                                    'attr' => ['class' => 'titulacion' ],
                                ])
            ->add('curso', ChoiceType::class, [
                                    'label' => 'Curso',
                                    'placeholder' => 'Todos o Seleccione Curso',
                                    'required' => false,
                                    'choices'  => [
                                        'Primero' => 1,
                                        'Segundo' => 2,
                                        'Tercero' => 3,
                                        'Cuarto' => 4,]
                                    ])
            ->add('asignatura', EntityType::class, [
                                    'label' => 'Asignatura',
                                    'required' => false,
                                    'placeholder' => 'Todas o Seleccione Asignatura',
                                    'class' => SgrAsignatura::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('profesor', EntityType::class,[
                                    'label' => 'Profesor',
                                    'required' => false,
                                    'placeholder' => 'Todos o Seleccione Profesor',
                                    'class' => SgrProfesor::class,
                                    'choice_label' => 'nombre',
                                ])
            ->add('f_inicio', DateTimeType::class, array(
                                    'date_label' => 'Fecha inicio',
                                    'widget' => 'single_text',
                                    'format' => 'dd/MM/yyyy',
                                    'html5' => false,
                                    'input' => 'string',
                                    'input_format' => 'd/m/Y',
                                    'view_timezone' => 'Europe/Madrid',
                                    'model_timezone' => 'Europe/Madrid',
                                    'attr' => ['class' => 'datetimepicker-input', 'data-target' => '#datetimepicker-fi'],
                                    'required' => true,                                  
                                    'constraints' => [  new NotBlank(),
                                                        new DateTime([ 'format' => 'd/m/Y']),
                                                         ],
            ))
            ->add('f_fin', DateTimeType::class, array(
                                    //'date_label' => 'Fecha Fin',
                                    'label' => 'Fecha Fin',
                                    'widget' => 'single_text',
                                    'format' => 'dd/MM/yyyy',
                                    'html5' => false,
                                    'input' => 'string',
                                    'input_format' => 'd/m/Y',
                                    'view_timezone' => 'Europe/Madrid',
                                    'model_timezone' => 'Europe/Madrid',
                                    'attr' => ['class' => 'datetimepicker-input', 'data-target' => '#datetimepicker-ff'],
                                    'required' => true,                                  
                                    'constraints' => [  new NotBlank(),
                                                        new DateTime(['format' => 'd/m/Y']),
                                                        /*new GreaterThanOrEqual( [
                                                            'propertyPath' => 'parent.all[f_inicio]',
                                                            //'value' => 'parent.all[f_inicio].normData',
                                                            'message' => "Debe ser igual o mayor que fecha inicio {{ value }} -- {{ compared_value }} -- {{ compared_value_type }}"] ),*/ 
                                                        ],
            ))
            ->add('espacio', EntityType::class,[
                                    'label' => 'Espacio',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Espacio',
                                    'class' => SgrEspacio::class,
                                    'choice_label' => 'nombre',
                                    'expanded' => true,
                                    'multiple' => true,
                                    'attr' => ['class' => 'form-check-inline'],
                                ])
            ->add('actividad', EntityType::class,[
                                    'label' => 'Actividad',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Actividad',
                                    'class' => SgrTipoActividad::class,
                                    'choice_label' => 'actividad',
                                ])
        ;

        //$builder->get('f_inicio')
        //    ->addModelTransformer($this->transformerDateTime);
        //$builder->get('f_fin')
        //    ->addModelTransformer($this->transformerDateTime);
        

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event)
            {
                //dump('asdads');
                //exit;
                
                $data = $event->getData();
                $form = $event->getForm();
                
                //No se añade al formulario de filtros cuando solo necesito f_inicio
                //dump('PRE_SUBMIT');
                //dump($data);
                //dump($form);
                //dump($form);
                //$data['f_inicio'] ? $f_inicio = $data['f_inicio']->format('d/m/Y'): $_f_inicio = false; 
                //$form->add('f_inicio',$f_inicio);
                
                if (!isset($data['f_fin']))
                {
                    //dump($data);
                    //exit;
                    //dump($form);
                    // en controller $data['f_fin'] == null
                    $form->add('f_fin', DateType::class, [ 'data' => date_create_from_format('d/m/Y', $data['f_inicio'], new \DateTimeZone('Europe/Madrid'))/*$data['f_inicio']*/ ]);
                }
                if ($data['termino'])
                {
                    
                    $choices = $this->entityManager->getRepository(SgrEspacio::class)->findBy([ 'termino' => $data['termino'] ]);    
                    $form->add('espacio', EntityType::class,[
                            'label' => 'Espacio',
                            'required' => false,
                            'placeholder' => 'Seleccione Espacio',
                            'class' => SgrEspacio::class,
                            'choices' => $choices,
                            'choice_label' => 'nombre',
                            'expanded' => true,
                            'multiple' => true,
                            'attr' => ['class' => 'form-check-inline'],
                        ]);
                }

                if ($data['titulacion'])
                {

                    $asignaturas = $this->entityManager->getRepository(SgrAsignatura::class)->findBy([ 'sgrTitulacion' => $data['titulacion'] ]);
                    $form->add('asignatura', EntityType::class, [
                                    'label' => 'Asignatura',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Asignatura',
                                    'class' => SgrAsignatura::class,
                                    'choices' => $asignaturas, 
                                    'choice_label' => 'nombre',
                                ]);
                    
                    $profesores = new ArrayCollection();
                    if ($asignaturas)
                    {
                        foreach ($asignaturas as $asignatura)
                        {
                            $grupos = $asignatura->getGrupos(); 
                            if ($grupos)
                            {
                                foreach ($grupos as $grupo)
                                {
                                    $profesors = $grupo->getSgrProfesors();
                                    if ($profesors)
                                    {
                                        foreach ($profesors as $profesor)
                                        {
                                            $profesores->add($profesor);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if(!$profesores->isEmpty())
                        $form->add('profesor', EntityType::class,[
                                    'label' => 'Profesor',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Profesor',
                                    'class' => SgrProfesor::class,
                                    'choices' => $profesores,
                                    'choice_label' => 'nombre',
                                ]);
                }
                //dump($data);
                //dump($form);
                //exit;
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

}