<?php

namespace App\Form;

use App\Entity\Product;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('productName',TextType::class,['attr' => array(
                'class' => 'form-control bg-gray',
                'placeholder' => 'Fill in the name of product'
            )])
            ->add('brand',TextType::class,['attr' => array(
                'class' => 'form-control bg-gray',
                'placeholder' => 'Fill in the brand of product'
            )])
            ->add('image',FileType::class, [
                'required' => false,
                'mapped' => false,
               'attr' => array('class' => 'form-control-file')
           ])
            ->add('description',TextareaType::class,['attr' => array(
                'class' => 'form-control bg-gray',
                'placeholder' => 'Fill in the description for product'
            )])
            ->add('quantity',TextType::class,['attr' => array(
                'class' => 'form-control bg-gray',
                'placeholder' => 'Fill in the quantity'
            )])
            ->add('price',TextType::class,['attr' => array(
                'class' => 'form-control bg-gray',
                'placeholder' => 'Fill in the price'
            )])
            ->add('CategoryId',EntityType::class,[
                'attr' => array(
                'class' => 'form-control',
            ),
            'class'=>'App\Entity\Category','choice_label'=>"name"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
