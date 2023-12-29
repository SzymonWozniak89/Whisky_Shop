<?php

namespace App\Form\Type;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label'=>'First name', 'required'=>true])
            ->add('lastName', TextType::class, ['label'=>'Last name', 'required'=>true])
            ->add('line1', TextType::class, ['label'=>'Address line 1', 'required'=>true])
            ->add('line2', TextType::class, ['label'=>'Address line 2', 'required'=>false])
            ->add('city', TextType::class, ['label'=>'City', 'required'=>true])
            ->add('state', TextType::class, ['label'=>'State', 'required'=>true])
            ->add('postalCode', TextType::class, ['label'=>'Postal code', 'required'=>true])
            ->add('submit', SubmitType::class, ['label'=>'Save'])
        ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver){
        $resolver->setDefaults([
            'data_class'=>Address::class,
            'csrf_protection'=>false
        ]);
    }
}