<?php
/**
 * Search type.
 */

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchType.
 */
class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'category',
            ChoiceType::class,
            [
                'label' => 'label.category ',
                'required' => true,
                'choices' => $this->prepareCategoriesForChoices(),
            ]
        );
        $builder->add(
            'value',
            TextType::class,
            [
                'label' => 'label.value',
                'required' => false,
            ]
        );
    }

    /**
     * Prepare Categories For Choices
     * @return array
     */
    protected function prepareCategoriesForChoices()
    {
        $categories = ['photo', 'user'];
        $choices = [];
        foreach ($categories as $category) {
            $choices[$category] = $category;
        }
        return $choices;
    }


    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Tag::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'search';
    }
}