<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Question;
use App\Entity\Proposition;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text')
            ->add('difficulty')
            ->add('noPictures')
            ->add(
                'propositionList',
                CollectionType::class,
                [
                    'entry_type' => PropositionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'Propositions',
                    'by_reference' => false,
                    'prototype' => true,
                    'prototype_name' => '__proposition_name__',
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([ 'data_class' => Question::class]);
    }
}

