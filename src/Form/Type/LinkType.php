<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 19/03/2017
 * Time: 13:40
 */

namespace WebLinks\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('url', UrlType::class);
    }

    public function getName()
    {
        return 'link';
    }
}
