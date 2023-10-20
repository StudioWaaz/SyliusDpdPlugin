<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class ShippingGatewayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'waaz.ui.dpd_username',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('password', TextType::class, [
                'label' => 'waaz.ui.dpd_password',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('customer_number', TextType::class, [
                'label' => 'waaz.ui.dpd_customer_number',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                    new Regex([
                        'pattern' => '/^\\d+$/',
                        'message' => 'waaz.dpd_customer_number_invalid',
                        'groups' => ['bitbag'],
                    ]),
                ],
            ])
            ->add('customer_centernumber', TextType::class, [
                'label' => 'waaz.ui.dpd_customer_centernumber',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('customer_countrycode', TextType::class, [
                'label' => 'waaz.ui.dpd_customer_countrycode',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
                'data' => 250,
            ])

            ->add('type', ChoiceType::class, [
                'label' => 'waaz.ui.dpd_type',
                'choices' => [
                    'DPD classic' => 'classic',
                    'DPD predict' => 'predict',
                    'DPD point relais' => 'relay',
                ],
                'data' => 'classic',
            ])

            ->add('sender_name', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_name',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_street', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_street',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_city', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_city',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_postalcode', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_postalcode',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_country', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_country',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                    new Length([
                        'max' => 2,
                        'maxMessage' => 'waaz.dpd_sender_country_invalid',
                        'groups' => ['bitbag'],
                    ]),
                ],
            ])
            ->add('sender_phone', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_phone',
                'required' => false,
            ])
            ->add('sender_email', TextType::class, [
                'label' => 'waaz.ui.dpd_sender_email',
                'required' => false,
            ])
            ->add('sender_commercial_address', CheckboxType::class, [
                'label' => 'waaz.ui.dpd_sender_commercial_address',
                'required' => false,
                'data' => true,
            ])
            ->add('printer_format', ChoiceType::class, [
                'label' => 'waaz.ui.dpd_printer_format.label',
                'required' => false,
                'choices' => [
                    'waaz.ui.dpd_printer_format.choices.pdf' => 'PDF',
                ],
                'data' => 'PDF',
            ])
        ;
    }
}
