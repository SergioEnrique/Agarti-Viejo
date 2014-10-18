<?php
namespace AG\PrincipalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NuevoProductoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Nombre', 'text');
        $builder->add('clave', 'text');
        $builder->add('Categoria', 'text', array('mapped' => false));
        $builder->add('Precio', 'number');
        $builder->add('Medidas', 'text');
        $builder->add('Contenido', 'textarea');
        $builder->add('Foto1', 'file', array('mapped' => false, 'required' => false));
        $builder->add('Foto2', 'file', array('mapped' => false, 'required' => false));
        $builder->add('Foto3', 'file', array('mapped' => false, 'required' => false));
        $builder->add('Agregar', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AG\PrincipalBundle\Entity\Producto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuevoproducto';
    }
}