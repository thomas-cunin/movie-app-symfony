<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('publishedAt', DateTimeType::class,['placeholder' => [
                'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
            ],'with_minutes' => false,'with_seconds' => false,
            'years' => range(date('1920'), date('Y')),
            ])
            ->add('director')
            ->add('category', EntityType::class, ['class' => Category::class,'choice_label'=>'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
