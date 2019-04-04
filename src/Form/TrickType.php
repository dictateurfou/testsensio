<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('category', EntityType::class, array('class' => Category::class,
                'choice_label' => 'getName',
                'placeholder' => 'Categorie', 'label' => 'Categorie', ))
            ->add('imageList', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'label' => false,
                'by_reference' => false,
                'entry_options' => array(
                    'attr' => array('class' => 'imageList-box', 'accept' => 'image/*'),
                ),
            ))
            ->add('videoList', CollectionType::class, array(
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => true,
                'prototype' => true,
                'label' => false,
                'entry_options' => array(
                    'attr' => array('class' => 'videoList-box'),
                ),
            ))
            ->add('valider', SubmitType::class, array('label' => 'Valider'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
