<?php

namespace App\Form;

use App\Entity\Advertisement;
use App\Form\DataTransformer\PriceToDecimalTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\File;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa ogłoszenia',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'PLN',
                'label' => 'Cena',
            ])
            ->add('images', FileType::class, [
                'label' => 'Zdjęcia (max 5)',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new Count([
                        'max' => 5,
                        'maxMessage' => 'Możesz przesłać maksymalnie 5 zdjęć.'
                    ]),
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '5M',
                                'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                                'mimeTypesMessage' => 'Proszę przesłać poprawny format obrazu (JPEG, PNG, GIF).'
                            ])
                        ]
                    ])
                ]
            ]);

        $builder->get('price')
            ->addModelTransformer(new PriceToDecimalTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
