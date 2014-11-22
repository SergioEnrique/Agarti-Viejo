<?php
namespace AG\PrincipalBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Foto
 */
class Foto
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $productoID;
    /**
     * @var string
     */
    private $path;
    /**
     * @var \AG\PrincipalBundle\Entity\Producto
     */
    private $producto;
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
     * Set productoID
     *
     * @param integer $productoID
     * @return Foto
     */
    public function setProductoID($productoID)
    {
        $this->productoID = $productoID;
        return $this;
    }
    /**
     * Get productoID
     *
     * @return integer 
     */
    public function getProductoID()
    {
        return $this->productoID;
    }
    /**
     * Set path
     *
     * @param string $path
     * @return Foto
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * Set producto
     *
     * @param \AG\PrincipalBundle\Entity\Producto $producto
     * @return Foto
     */
    public function setProducto(\AG\PrincipalBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;
        return $this;
    }
    /**
     * Get producto
     *
     * @return \AG\PrincipalBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/productos/'.$this->producto->getId();
    }
    /**
     * 
     */
    private $file;
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );
        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}