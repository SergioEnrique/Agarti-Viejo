<?php
// src/AG/PrincipalBundle/Servicios/Producto.php
namespace AG\PrincipalBundle\Servicios;

class Producto
{
    protected $em; // Doctrine Entity Manager
    protected $router; // Para gererar rutas url

    public function __construct($router, $em)
    {
    	$this->em = $em;
    	$this->router = $router;
    }

	public function getSlug($nombreOriginal)
    {
        return $this->toAscii($nombreOriginal);
    }

    private function toAscii($str)
    {   
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_| -]+/", '-', $clean);

        return $clean;
    }
 
}