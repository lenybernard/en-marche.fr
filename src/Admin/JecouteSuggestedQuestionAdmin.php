<?php

namespace AppBundle\Admin;

use AppBundle\Form\Jecoute\ChoiceFormType;
use AppBundle\Form\Jecoute\QuestionChoiceType;
use AppBundle\Jecoute\SurveyQuestionTypeEnum;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JecouteSuggestedQuestionAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_per_page' => 32,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Questions du panier', ['class' => 'col-md-6'])
                ->add('content', TextType::class, [
                    'filter_emojis' => true,
                    'label' => 'Question',
                ])
                ->add('type', QuestionChoiceType::class, [
                    'label' => 'Type de question',
                ])
                ->add('choices', CollectionType::class, [
                    'entry_type' => ChoiceFormType::class,
                    'required' => false,
                    'label' => 'Réponses',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
                ->add('published', CheckboxType::class, [
                    'label' => 'Publier la question panier',
                    'required' => false,
                ])
            ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('content', null, [
                'label' => 'Question',
            ])
            ->add('type', 'choice', [
                'choices' => array_flip(SurveyQuestionTypeEnum::all()),
                'label' => 'Type de question',
            ])
            ->add('choices', 'array', [
                'label' => 'Réponses',
                'template' => 'admin/jecoute/suggested_question_choices_list.html.twig',
            ])
            ->add('published', null, [
                'label' => 'Publié',
                'editable' => true,
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }
}
