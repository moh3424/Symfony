<?php

namespace BoutiqueBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType; // input type password
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // input type Integer
use Symfony\Component\Form\Extension\Core\Type\EmailType; // input type email
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
                new ASSERT\Length(array(
                    'min' => 3,
                    'max' => 20,
                    'minMessage' => 'Veuillez saisir mini 3 caractères',
                    'maxMessage' => 'Veuillez saisirmax 20 caractères',

                ))
            )
        ))
        ->add('categorie')
        ->add('titre')
        ->add('description')
        ->add('couleur')
        ->add('taille')
        ->add('public')
        ->add('photo')
        ->add('prix')
        ->add('stock')
        ->add('Enregistrer');
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
