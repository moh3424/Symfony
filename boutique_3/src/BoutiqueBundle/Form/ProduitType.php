<?php

namespace BoutiqueBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\FileType; // input type password
use Symfony\Component\Form\Extension\Core\Type\MoneyType; // input type password
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // input type Integer
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // input type checkbox
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints as Assert;


class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('reference', TextType::class, array(
            'required' => false,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Veuillez remplir ce champs'
                )),
                new Assert\Length(array(
                    'min' => 3,
                    'max' => 20,
                    'minMessage' => 'Veuillez saisir mini 3 caractères',
                    'maxMessage' => 'Veuillez saisirmax 20 caractères',

                ))
            )
        ))
        ->add('categorie', TextType::class, array(
            'required' => false,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message'=> 'Veuillez renseigner ce champs'
                )),
                new Assert\length(array(
                    'min'=> 3,
                    'minMessage' => 'Veuillez saisir min 3 caractères',
                    'max'=> 30,
                    'maxMessage' => 'Veuillez saisir max 30 caractères'
                )),
            )
        ))
        ->add('titre', TextType::class, array(
            'required' => false,
        ))
        ->add('description',TextareaType::class, array(
            'required' => false,
        ))
        ->add('couleur')
        ->add('taille', TextType::class, array(
            'required' => false,
        ))
        ->add('public', ChoiceType::class, array(
            'required' => false,
            'choices' => array(
                'Femme' => 'f',
                'Homme' => 'm'
            )

        ))
        ->add('file', FileType::class, array(
            'required' => false,
            'constraints'=> array(
                new Assert\File(array(
                    'maxSize' => '3M',
                    'maxSizeMessage' => 'Veuillez uploader une ilmage de 3 Mo maximum'
                )),
            ),
        ))
        ->add('prix', MoneyType::class, array(
            'required' => false,
        ))
        ->add('stock', IntegerType::class, array(
            'required' => false,
        ))
        ->add('Enregistrer', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoutiqueBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boutiquebundle_produit';
    }


}
