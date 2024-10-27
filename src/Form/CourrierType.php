<?php

namespace App\Form;

use App\Entity\Courrier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CourrierType extends AbstractType
{ 
  private $security;
  public function __construct(Security $s)
  {
    $this->security = $s;
  }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
      $currentUser = $this->security->getUser();
    //   $currentStatus = $this->security->getCurrentStatus();
        $builder
            ->add('date_envoi', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'envoi',
                'required' => true,
            ])
            ->add('date_reception', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de réception',
                'required' => false,
            ])
            ->add('expediteur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Expéditeur',
                'required' => true,
                'data' => $currentUser, 
                'disabled' => true,
            ])
            ->add('destinataire', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Destinataire(s)',
            ])
            ->add('objet', TextareaType::class, [
                'label' => 'Objet (Texte uniquement)',
                'required' => true,
                'attr' => ['rows' => 5],
            ])
            ->add('expedCourrier', TextType::class, [
                'label' => 'Expéditeur du courrier',
                'required' => true,
            ])
            // ->add('status', ChoiceType::class, [
            //     'choices' => [
            //         'En attente' => 'en_attente',
            //         'Envoyé' => 'envoye',
            //         'Reçu' => 'recu',
            //         'disable' => 'true'
            //     ],
            //     'label' => 'Statut',
            //     'required' => true,
            // ])
            ->add('piece_jointe', FileType::class, [
                'label' => 'Pièce jointe (fichier, document, image, etc.)',
                'mapped' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courrier::class,
        ]);
    }
}
