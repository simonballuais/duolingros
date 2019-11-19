<?php
namespace App\Form;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Proposition;

class PropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add(
                'rightAnswer',
                null,
                [
                    'label' => false,
                ]
            )
            ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function ($event) {
                $proposition = $event->getData();

                $event->getForm()->add(
                    'image',
                    TextType::class,
                    [
                        'attr' => [
                            'class' => 'proposition-file-input',
                            'proposition-id' => $proposition ? $proposition->getId() : null,
                        ],
                        'mapped' => false,
                        'required' => false,
                    ]
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([ 'data_class' => Proposition::class]);
    }
}

