<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Recipes;
use App\Listeners\FormListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function __construct(private FormListener $formListener)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('slug', TextType::class, [
                'required' => false
            ])
            ->add('recipes', EntityType::class, [
                'class' => Recipes::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->formListener->autoslug('name'))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListener->timestamp())
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
