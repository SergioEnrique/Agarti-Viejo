<?php

namespace AG\PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 */
class Producto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $categoryID;

    /**
     * @var string
     */
    private $clave;

    /**
     * @var string
     */
    private $Nombre;

    /**
     * @var float
     */
    private $Precio;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $fotos;

    /**
     * @var \AG\PrincipalBundle\Entity\Category
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fotos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set categoryID
     *
     * @param integer $categoryID
     * @return Producto
     */
    public function setCategoryID($categoryID)
    {
        $this->categoryID = $categoryID;

        return $this;
    }

    /**
     * Get categoryID
     *
     * @return integer 
     */
    public function getCategoryID()
    {
        return $this->categoryID;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return Producto
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set Nombre
     *
     * @param string $nombre
     * @return Producto
     */
    public function setNombre($nombre)
    {
        $this->Nombre = $nombre;

        return $this;
    }

    /**
     * Get Nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->Nombre;
    }

    /**
     * Set Precio
     *
     * @param float $precio
     * @return Producto
     */
    public function setPrecio($precio)
    {
        $this->Precio = $precio;

        return $this;
    }

    /**
     * Get Precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->Precio;
    }

    /**
     * Add fotos
     *
     * @param \AG\PrincipalBundle\Entity\Foto $fotos
     * @return Producto
     */
    public function addFoto(\AG\PrincipalBundle\Entity\Foto $fotos)
    {
        $this->fotos[] = $fotos;

        return $this;
    }

    /**
     * Remove fotos
     *
     * @param \AG\PrincipalBundle\Entity\Foto $fotos
     */
    public function removeFoto(\AG\PrincipalBundle\Entity\Foto $fotos)
    {
        $this->fotos->removeElement($fotos);
    }

    /**
     * Get fotos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * Set category
     *
     * @param \AG\PrincipalBundle\Entity\Category $category
     * @return Producto
     */
    public function setCategory(\AG\PrincipalBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AG\PrincipalBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var string
     */
    private $Slug;

    /**
     * @var string
     */
    private $Medidas;

    /**
     * @var string
     */
    private $Contenido;


    /**
     * Set Slug
     *
     * @param string $slug
     * @return Producto
     */
    public function setSlug($slug)
    {
        $this->Slug = $slug;

        return $this;
    }

    /**
     * Get Slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->Slug;
    }

    /**
     * Set Medidas
     *
     * @param string $medidas
     * @return Producto
     */
    public function setMedidas($medidas)
    {
        $this->Medidas = $medidas;

        return $this;
    }

    /**
     * Get Medidas
     *
     * @return string 
     */
    public function getMedidas()
    {
        return $this->Medidas;
    }

    /**
     * Set Contenido
     *
     * @param string $contenido
     * @return Producto
     */
    public function setContenido($contenido)
    {
        $this->Contenido = $contenido;

        return $this;
    }

    /**
     * Get Contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->Contenido;
    }
}
