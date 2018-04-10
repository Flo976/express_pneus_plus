<?php

namespace FD\mediadminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class FormCheckType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('check1', CheckBoxType::class, array(
                'required' => true,
                'label' => 'J’accepte les règles de confidentialité et m’engage à ne pas contacter directement les photographes cités ci-après.',
                
                ))
            ->add('check2', CheckBoxType::class, array(
                'required' => true,
                'label' => 'Tout contact avec les photographes doit être validé par l’agence,
la facturation étant assurée dans sa totalité par Only France.',
                

                ))
            
           
            ;
           
                 
            
    }

   
}