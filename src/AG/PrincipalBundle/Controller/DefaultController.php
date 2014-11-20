<?php

namespace AG\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AG\PrincipalBundle\Entity\Producto;
use AG\PrincipalBundle\Entity\Category;
use AG\PrincipalBundle\Entity\Foto;

use AG\PrincipalBundle\Form\Type\NuevoProductoType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AGPrincipalBundle:Default:index.html.twig');
    }

    public function vistaCategoriaAction($categoriaSlug)
    {
        $em = $this->getDoctrine()->getManager();

        // Obteniendo Categoría
        $categoryEntity = $em->getRepository("AGPrincipalBundle:Category");
        $categoryObject = $categoryEntity->findOneBy(array("slug" => $categoriaSlug));

        // Obteniendo Productos
        $productoEntity = $em->getRepository("AGPrincipalBundle:Producto");
        $productosArrayObjects =  $productoEntity->findBy(array("category" => $categoryObject));
        
        return $this->render('AGPrincipalBundle:Default:category.html.twig', array(
            "category" => $categoryObject,
            "productos" => $productosArrayObjects,
        ));
    }

    public function catalogoAdminAction()
    {
        return $this->render('AGPrincipalBundle:Default:catalogoAdmin.html.twig');
    }
    public function nuevoProductoAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	$nuevoProducto = new Producto();
    	$formProducto = $this->createForm(new NuevoProductoType(), $nuevoProducto);

    	// Recuperando formularios
        if('POST' === $request->getMethod()) {
            if($request->request->has($formProducto->getName())) {

                // Cargar el formulario contestado y procesarlo si es correcto
                $formProducto->handleRequest($request);
                if($formProducto->isValid())
                {
                    // Servicio para productos
                    $productoService = $this->get('producto_service');

                    // Obtener categoría
                    $categoryEntity = $em->getRepository('AGPrincipalBundle:Category');
                    $category = $categoryEntity->findOneById($formProducto['Categoria']->getData());

                    // Settear Categoría y Slug
                    $nuevoProducto->setCategory($category);
                    $nuevoProducto->setSlug($productoService->getSlug($nuevoProducto->getNombre()));

                    // Persistir el nuevo producto y sus fotos
                    $em->persist($nuevoProducto);
                    $em->flush();

                    // Obtener fotos y agregarlas a la base de datos
                    if($formProducto["Foto1"]->getData())
                    {
                        $nuevaFoto1 = new Foto();
                        $nuevaFoto1->setProducto($nuevoProducto);
                        $nuevaFoto1->setFile($formProducto["Foto1"]->getData());
                        $nuevaFoto1->upload();
                        $em->persist($nuevaFoto1);
                    }
                    if($formProducto["Foto2"]->getData())
                    {
                        $nuevaFoto2 = new Foto();
                        $nuevaFoto2->setProducto($nuevoProducto);
                        $nuevaFoto2->setFile($formProducto["Foto2"]->getData());
                        $nuevaFoto2->upload();
                        $em->persist($nuevaFoto2);
                    }
                    if($formProducto["Foto3"]->getData())
                    {
                        $nuevaFoto3 = new Foto();
                        $nuevaFoto3->setProducto($nuevoProducto);
                        $nuevaFoto3->setFile($formProducto["Foto3"]->getData());
                        $nuevaFoto3->upload();
                        $em->persist($nuevaFoto3);
                    }

                    $em->flush();

                    // Se manda un mensaje de travesura realizada
                    $this->get('session')->getFlashBag()->set(
                        'notice',
                        'El nuevo producto ('.$nuevoProducto->getNombre().') ha sido agregado a la base de datos.'
                    );

                    // Se hace un nuevo formulario
                    $nuevoProducto = new Producto();
                    $formProducto = $this->createForm(new NuevoProductoType(), $nuevoProducto);
                }
            }
        }

        return $this->render('AGPrincipalBundle:Default:nuevoProducto.html.twig', array(
        	'formProducto' => $formProducto->createView(),
        ));
    }
    public function nuevoPedidoAction()
    {
        return $this->render('AGPrincipalBundle:Default:nuevoPedido.html.twig', array(
            'a' => 'a',
        ));
    }
}