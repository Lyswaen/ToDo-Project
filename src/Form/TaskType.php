<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The TaskType class is used to generate the form bind to the Task entity.
 *
 * Class TaskType
 * @package App\Form
 */
class TaskType extends AbstractType
{
    /**
     * The buildForm() method is a customizable factory for the form that you want.
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Nom de la tâche',
            ])
            ->add('content', null, [
                'label' => 'Contenu de la tâche'
            ]);
    }

    /**
     * The configurationOption() method is simply the configuration for the form.
     *
     * There are many options that you can use, such as __*data_class*__ which is the entity bound to the form.
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
