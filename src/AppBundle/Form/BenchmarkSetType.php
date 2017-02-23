<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 12:50
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form class for creating benchmark set
 *
 * Class BenchmarkSetType
 * @package AppBundle\Form
 */
class BenchmarkSetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('benchmarkedWebsite', UrlType::class, [
                'label' => 'Website url you want to benchmark: '
            ])
            ->add('competitorsWebsites', TextType::class, [
                'label' => 'Websites urls you want to compare with (separated by space): ',
                'attr' => ['class' => 'competitors']
            ])
            ->add('submit', SubmitType::class, array(
                    'label' => 'Run benchmark')
            );

        //competitors url will be returned in array but displayed as string
        $builder
            ->get('competitorsWebsites')->addModelTransformer(new CallbackTransformer(
            function ($urlsAsArray) {
                // transform the array to a string
                return implode(' ', $urlsAsArray);
            },
            function ($urlsAsString) {
                // transform the string back to an array
                return explode(' ', $urlsAsString);
            }
        ))
        ;
    }
}