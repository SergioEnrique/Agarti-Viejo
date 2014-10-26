<?php

namespace AG\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AG\PrincipalBundle\Entity\Producto;
use AG\PrincipalBundle\Entity\Category;

use AG\PrincipalBundle\Form\Type\NuevoProductoType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AGPrincipalBundle:Default:index.html.twig');
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

                $formProducto->handleRequest($request);

                if($formProducto->isValid())
                {
                    $productoService = $this->get('producto_service');

                    $categoryEntity = $em->getRepository('AGPrincipalBundle:Category');
                    $category = $categoryEntity->findOneById($formProducto['Categoria']->getData());

                    $nuevoProducto->setCategory($category);
                    $nuevoProducto->setSlug($productoService->getSlug($nuevoProducto->getNombre()));

                	$em->persist($nuevoProducto);
                    $em->flush();
                }
            }
        }

        return $this->render('AGPrincipalBundle:Default:nuevoProducto.html.twig', array(
        	'formProducto' => $formProducto->createView(),
        ));
    }
}