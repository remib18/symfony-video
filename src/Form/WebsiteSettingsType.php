<?php

namespace App\Form;

use App\Entity\HomePages;
use App\Entity\WebsiteSettings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WebsiteSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active_homepage', EntityType::class, [
                'class' => HomePages::class,
                'choice_label' => 'label',
            ])
            ->add('markdownContent', TextareaType::class, [
                'mapped' => false,
                'data' => $builder->getData()->getActiveHomepage() ? $builder->getData()->getActiveHomepage()->getMarkdown() : '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WebsiteSettings::class,
        ]);
    }
}
