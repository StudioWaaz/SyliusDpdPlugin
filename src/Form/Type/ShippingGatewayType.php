<?php

declare(strict_types=1);

namespace Waaz\SyliusDpdPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ShippingGatewayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('account_number', TextType::class, [
                'label' => 'waaz.ui.tnt_account_number',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_name', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_name',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_address1', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_address1',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_address2', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_address2',
                'required' => false,
            ])
            ->add('sender_city', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_city',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_zip_code', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_zip_code',
                'constraints' => [
                    new NotBlank(['groups' => ['bitbag']]),
                ],
            ])
            ->add('sender_phone_number', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_phone_number',
                'required' => false,
            ])
            ->add('sender_contact_first_name', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_contact_first_name',
                'required' => false,
            ])
            ->add('sender_contact_last_name', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_contact_last_name',
                'required' => false,
            ])
            ->add('sender_email_address', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_email_address',
                'required' => false,
            ])
            ->add('sender_fax_number', TextType::class, [
                'label' => 'waaz.ui.tnt_sender_fax_number',
                'required' => false,
            ])
            ->add('sender_type', ChoiceType::class, [
                'label' => 'waaz.ui.tnt_sender_type',
                'required' => false,
                'choices' => [
                    'waaz.ui.tnt.enterprise' => 'ENTERPRISE',
                    'waaz.ui.tnt.depot' => 'DEPOT',
                ],
                'data' => 'ENTERPRISE',
            ])
            ->add('receiver_type', ChoiceType::class, [
                'label' => 'waaz.ui.tnt_receiver_type',
                'required' => false,
                'choices' => [
                    'waaz.ui.tnt.enterprise' => 'ENTERPRISE',
                    'waaz.ui.tnt.depot' => 'DEPOT',
                    'waaz.ui.tnt.drop_off_point' => 'DROPOFFPOINT',
                    'waaz.ui.tnt.individual' => 'INDIVIDUAL',
                ],
                'data' => 'INDIVIDUAL',
            ])
            ->add('label_format', ChoiceType::class, [
                'label' => 'waaz.ui.tnt_label_format',
                'required' => false,
                'choices' => [
                    'waaz.ui.tnt_label.stda4' => 'STDA4',
                    'waaz.ui.tnt_label.thermal' => 'THERMAL',
                    'waaz.ui.tnt_label.thermal_no_logo' => 'THERMAL,NO_LOGO',
                    'waaz.ui.tnt_label.thermal_rotate_180' => 'THERMAL,ROTATE_180',
                    'waaz.ui.tnt_label.thermal_no_logo_rotate_180' => 'THERMAL,NO_LOGO,ROTATE_180',
                ],
                'data' => 'STDA4',
            ])
        ;
    }
}
