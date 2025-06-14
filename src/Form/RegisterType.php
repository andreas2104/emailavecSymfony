<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Correction de l'import de TextType
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; // Import du bouton de soumission
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add("nom", TextType::class, [
        'label' => 'Nom',
        'attr' => ['placeholder' => 'Votre nom'] 
      ])
      ->add('prenom', TextType::class, [
        'label' => 'Prénom',
        'attr' => ['placeholder' => 'Votre prénom']
      ])
      ->add('email', EmailType::class, [
        'label' => 'Email'
      ])
      ->add('password', PasswordType::class, [
        'label' => 'Mot de passe'
      ])
      ->add('submit', SubmitType::class, [ 
        'label' => 'S\'inscrire'
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
