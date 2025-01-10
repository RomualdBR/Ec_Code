<?php

namespace App\Form;

use App\Entity\BookRead;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBookReadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id')
            ->add('book_id')
            ->add('rating')
            ->add('description')
            ->add('is_read')
            ->add('cover')
            ->add('created_at', null, [
                'widget' => 'single_text'
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookRead::class,
        ]);
    }
}
