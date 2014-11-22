<?php

namespace AG\PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderImgType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Imagen', 'file', array('mapped' => false, 'required' => true))
            ->add('link', 'url', array('required' => false))
            ->add('Agregar', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AG\PrincipalBundle\Entity\SliderImg'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ag_principalbundle_sliderimg';
    }
}
