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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Persistence\ObjectManager;
use FD\mediadminBundle\Form\DataTransformer\TagsToCollectionTransformer;

class ThemeType extends AbstractType
{
     private $manager;
 
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('nom', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Nom de la voiture (ex: Peugeot 406)')

                ))
            ->add('soustitre', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Ajouter un sous-titre (ex: Serie S)')

                ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Ajouter la description du véhicule')

                ))
            ->add('kilometrage', NumberType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Kilométrage (ex: 154000)')

                ))
            ->add('annee', NumberType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Année du modèle (ex : 2007)')

                ))
            ->add('prix', NumberType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Prix de la voiture')

                ))
            ->add('nbportes', NumberType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Nombre de portes')

                ))
            ->add('pfisc', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Puissance Fiscale')

                ))
            ->add('pdin', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Puissance Din en CV')

                ))
            ->add('typeboite', ChoiceType::class, array(
                'choices'  => array(
                    'Type de boite' => null,
                    'Boite Automatique' => 'Automatique',
                    'Boite Manuelle' => 'Manuelle',
                ),
                'required' => true,
                'attr' => array(
                'placeholder' => 'Type de boite')

                ))
            ->add('energie', ChoiceType::class, array(
                'choices'  => array(
                    'Energie' => null,
                    'Diesel' => 'Diesel',
                    'Essence' => 'Essence',
                    'Electrique' => 'Electrique',

                ),
                'required' => true,
                'attr' => array(
                'placeholder' => 'Type de boite')

                ))
            ->add('datemec', DateType::class, array(
                'widget' => 'choice',
                 'years' => range(date('Y'), date('Y')-50),
                 
                
                'required' => true,
                 'label' => 'Date de Mise en Circulation :',
                'attr' => array(

                'placeholder' => 'date')

                ))
            ->add('couleurin', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Couleur Intérieure')

                ))
            ->add('couleurext', TextType::class, array(
                'required' => true,
                'attr' => array(
                'placeholder' => 'Couleur Extérieure')

                ))
            ->add('premieremain', ChoiceType::class, array(
                'choices'  => array(
                    'Séléctionner' => null,
                    'Oui' => true,
                    'Non' => false,
                ),
                 'label' => 'Premiere Main ?',
                'required' => true,
                'attr' => array(
                'placeholder' => 'Premiere Main ?')

                ))
            ->add('active', ChoiceType::class, array(
                'choices'  => array(
                    'Séléctionner' => null,
                    'Oui' => true,
                    'Non' => false,
                ),
                 'label' => 'Activer l\'Annonce ?',
                'required' => true,
                'attr' => array(
                'placeholder' => 'Activer l\'Annonce ?')

                ))
            ->add('vip', ChoiceType::class, array(
                'choices'  => array(
                    'Séléctionner' => null,
                    'Oui' => true,
                    'Non' => false,
                ),
                 'label' => 'Annonce VIP ?',
                'required' => true,
                'attr' => array(
                'placeholder' => 'Annonce VIP ?')

                ))
            ->add('tags', CollectionType::class, array(
                    'entry_type' => TagType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'Options et Equipements',
                    'required' => false
                ))
            ;
           
             // Data Transformer
            $builder
                ->get('tags')
                ->addModelTransformer(new TagsToCollectionTransformer($this->manager));     
            
    }

   public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FD\mediadminBundle\Entity\Theme',
        ));
    }
}