<?php

namespace App\Form;

use App\Entity\Courrier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourrierType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('type')
      ->add('expediteur', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'email',
        'label' => 'expediteur'
      ])
      ->add('destinataire', EntityType::class, [
        'class' => User::class,
        'choice_label' => 'email',
        'multiple' => true,
        'expanded' => false,
        'label' => 'Destinataire'
      ])

      ->add('date_envoi')
      ->add('date_reception')

      ->add('objet', TextType::class, [
        'label' => 'Objet (Texte uniquement)',
        'required' => true,  // Le champ est obligatoire
      ])

      ->add('message', TextType::class, [
        'label' => 'Message',
        'required' => true,  // Le champ est obligatoire
      ])

      ->add('piece_jointe', FileType::class, [
        'label' => 'Pièce jointe (fichier, document, image, etc.)',
        'mapped' => false,
        'required' => false,
      ])

      ->add('submit', SubmitType::class, [
        'label' => 'envoier'
      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Courrier::class,
    ]);
  }
}
