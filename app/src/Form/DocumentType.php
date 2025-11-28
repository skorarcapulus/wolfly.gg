<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Overlay;
use App\Enum\DocumentType as DocumentTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'z.B. Haupt-HTML',
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
            ->add('type', EnumType::class, [
                'label' => 'Dokumenttyp',
                'class' => DocumentTypeEnum::class,
                'attr' => [
                    'class' => 'form-control',
                ],
                'choice_label' => function (DocumentTypeEnum $type): string {
                    return match ($type) {
                        DocumentTypeEnum::HTML => 'HTML/Twig',
                        DocumentTypeEnum::CSS => 'CSS',
                        DocumentTypeEnum::JS => 'JavaScript',
                    };
                },
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Bitte wähle einen Dokumenttyp',
                    ]),
                ],
            ])
            ->add('source', TextareaType::class, [
                'label' => 'Quellcode',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 15,
                    'placeholder' => 'Füge hier deinen Code ein...',
                    'style' => 'font-family: monospace;',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Bitte gib den Quellcode ein',
                    ]),
                ],
            ])
            ->add('overlay', EntityType::class, [
                'label' => 'Overlay (optional)',
                'class' => Overlay::class,
                'choice_label' => 'title',
                'placeholder' => 'Kein Overlay zuweisen',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'help' => 'Optional: Weise dieses Dokument einem Overlay zu',
            ])
            ->add('isReleased', CheckboxType::class, [
                'label' => 'Veröffentlicht',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'help' => 'Aktiviere diese Option, um das Dokument direkt zu veröffentlichen',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
