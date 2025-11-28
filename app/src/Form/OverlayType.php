<?php

namespace App\Form;

use App\Entity\Overlay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OverlayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'z.B. Mein Stream Overlay',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Bitte gib einen Titel ein',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Der Titel muss mindestens {{ limit }} Zeichen lang sein',
                        'maxMessage' => 'Der Titel darf maximal {{ limit }} Zeichen lang sein',
                    ]),
                ],
            ])
            ->add('isReleased', CheckboxType::class, [
                'label' => 'Veröffentlicht',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'help' => 'Aktiviere diese Option, um das Overlay direkt zu veröffentlichen',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Overlay::class,
        ]);
    }
}
