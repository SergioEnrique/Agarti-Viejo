<?php
namespace AG\PrincipalBundle\Twig;

class SliderExtension extends \Twig_Extension
{
    protected $em; // Doctrine Entity Manager

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getName()
    {
        return 'slider_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sliderImages', array($this, 'get_slider_images')),
        );
    }

    public function get_slider_images()
    {
        // Obtener repositorio de imagenes del slider
        // Obtener las imágenes de slider existentes
        $sliderImgRepository = $this->em->getRepository('AGPrincipalBundle:SliderImg');
        $sliderImgCollection = $sliderImgRepository->findAll();

        return $sliderImgCollection;
    }
}