<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use AppBundle\Entity\Lesson;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'questionList',
                CollectionType::class,
                [
                    'entry_type' => QuestionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'Questions',
                    'by_reference' => false,
                ]
            )
            ->add(
                'translationList',
                CollectionType::class,
                [
                    'entry_type' => TranslationType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'Traductions',
                    'by_reference' => false,
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([ 'data_class' => Lesson::class]);
    }
    }
?>
