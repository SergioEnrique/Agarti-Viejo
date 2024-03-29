<?php

namespace AG\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AG\PrincipalBundle\Entity\Producto;
use AG\PrincipalBundle\Entity\Category;
use AG\PrincipalBundle\Entity\Foto;
use AG\PrincipalBundle\Entity\SliderImg;

use AG\PrincipalBundle\Form\Type\NuevoProductoType;
use AG\PrincipalBundle\Form\SliderImgType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AGPrincipalBundle:Default:index.html.twig');
    }

    public function conocenosAction()
    {
        return $this->render('AGPrincipalBundle:Default:conocenos.html.twig');
    }

    public function serviciosAction()
    {
        return $this->render('AGPrincipalBundle:Default:servicios.html.twig');
    }

    public function listaPreciosAction(Request $request)
    {
        // Recuperando formularios
        if('POST' === $request->getMethod()) {

            // Información del contacto
            $nombre = $this->get('request')->request->get('nombre');
            $email = $this->get('request')->request->get('email');

            // Obtener lista de todos los productos
            $em = $this->getDoctrine()->getManager();
            $productosRepository = $em->getRepository('AGPrincipalBundle:Producto');
            $productosCollection = $productosRepository->findAll();

            // Correo con la lista de precios al cliente
            $message = \Swift_Message::newInstance()
            ->setSubject("Lista de precios - Agarti")
            ->setFrom("noreply@agarti.com.mx")
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'AGPrincipalBundle:Default:listaPreciosEmail.txt.twig', array(
                        'nombre' => $nombre,
                        'productos' => $productosCollection,
                    )
                )
            );
            $this->get('mailer')->send($message);

            // Correo para notificar que alguien descargó la lista de precios
            $message = \Swift_Message::newInstance()
            ->setSubject("Descargaron la Lista de Precios en Agarti")
            ->setFrom("noreply@agarti.com.mx")
            ->setTo("svargas@agarti.com.mx")
            ->setBody(
                $this->renderView(
                    'AGPrincipalBundle:Default:alguienDescargoListaPrecios.txt.twig', array(
                        'nombre' => $nombre,
                        'email' => $email,
                    )
                )
            );
            $this->get('mailer')->send($message);

            // Se manda un mensaje de travesura realizada
            $this->get('session')->getFlashBag()->set(
                'notice',
                'Se ha enviado exitosamente la lista de precios a su correo. Si tiene problemas para recibirlo o tiene alguna duda, contacte con nosotros en contacto@agarti.com.mx'
            );
        }

        return $this->redirect($this->generateUrl('ag_principal_homepage'));
    }

    public function contactoAction(Request $request)
    {
        // Se crea el formulario de  contacto
        $formContacto = $this->createFormBuilder()
            ->add('Asunto', 'text')
            ->add('Nombre', 'text')
            ->add('Email', 'email')
            ->add('Mensaje', 'textarea')
            ->add('Enviar', 'submit')
            ->getForm();

        // Recumerando formulario
        $formContacto->handleRequest($request);
        if($formContacto->isValid()) {

            // Crear correo y enviarlo con los datos del formulario
            $message = \Swift_Message::newInstance()
            ->setSubject("Mensaje Agarti - ".$formContacto["Asunto"]->getData())
            ->setFrom($formContacto["Email"]->getData())
            ->setTo('agendas.agarti@gmail.com')
            ->setBody(
                $this->renderView(
                    'AGPrincipalBundle:Default:contactoEmail.txt.twig',
                    array(
                        'asunto' => $formContacto["Asunto"]->getData(),
                        'nombre' => $formContacto["Nombre"]->getData(),
                        'email' => $formContacto["Email"]->getData(),
                        'mensaje' => $formContacto["Mensaje"]->getData(),
                    )
                )
            );
            $this->get('mailer')->send($message);

            // Se manda un mensaje de travesura realizada
            $this->get('session')->getFlashBag()->set(
                'notice',
                'Su mensaje ha sido recibido con éxito. Responderemos lo más pronto posible.'
            );
        }

        return $this->render('AGPrincipalBundle:Default:contacto.html.twig', array(
            'formContacto' => $formContacto->createView(),
        ));
    }

    public function preciosEspecialesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productosRepository = $em->getRepository('AGPrincipalBundle:Producto');
        $productosCollection = $productosRepository->findAll();

        return $this->render('AGPrincipalBundle:Default:preciosEspeciales.html.twig', array(
            "productos" =>  $productosCollection,
        ));
    }

    public function sliderAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Generar un nuevo objeto imagen de slider por si se sube
        $nuevaImagen = new SliderImg();
        $formSliderImg = $this->createForm(new SliderImgType(), $nuevaImagen);

        // Recuperando formularios
        if('POST' === $request->getMethod()) {
            if($request->request->has($formSliderImg->getName())) {

                // Cargar el formulario contestado y procesarlo si es correcto
                $formSliderImg->handleRequest($request);
                if($formSliderImg->isValid())
                {

                    // Subir imágen
                    $nuevaImagen->setFile($formSliderImg["Imagen"]->getData());
                    $nuevaImagen->upload();
                    $em->persist($nuevaImagen);
                    $em->flush();

                    // Se manda un mensaje de travesura realizada
                    $this->get('session')->getFlashBag()->set(
                        'notice',
                        'Travesura realizada: Imágen agregada al slider y almacenada en la base de datos.'
                    );

                    // Nuevo formulario para agregar más imágenes
                    $nuevaImagen = new SliderImg();
                    $formSliderImg = $this->createForm(new SliderImgType(), $nuevaImagen);
                }
            }
        }

        // Obtener las imágenes de slider existentes
        $sliderImgRepository = $em->getRepository('AGPrincipalBundle:SliderImg');
        $sliderImgCollection = $sliderImgRepository->findAll();

        return $this->render('AGPrincipalBundle:Default:sliderConfig.html.twig', array(
            'formSliderImg' => $formSliderImg->createView(), 
            'imagenes' => $sliderImgCollection,
        ));
    }

    public function borrarSliderImgAction($imagenId)
    {
        $em = $this->getDoctrine()->getManager();

        $sliderImgRepository = $em->getRepository('AGPrincipalBundle:SliderImg');
        $imagenObject = $sliderImgRepository->find($imagenId);

        $em->remove($imagenObject);
        $em->flush();

        // Se manda un mensaje de travesura realizada
        $this->get('session')->getFlashBag()->set(
            'notice',
            'Travesura realizada: La imagen se ha eliminado del slider y la base de datos.'
        );

        return $this->redirect($this->generateUrl('ag_principal_slider_admin'));
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

    public function vistaProductoAction($categoriaSlug, $productoSlug)
    {
        $em = $this->getDoctrine()->getManager();

        // Repositorio de Productos
        $productosRepository = $em->getRepository("AGPrincipalBundle:Producto");

        // Buscar producto
        $productoObject = $productosRepository->findOneBy(array("Slug" => $productoSlug));

        // Repositorio de categorías
        $categoriesRepository = $em->getRepository("AGPrincipalBundle:Category");

        // Buscar categoría
        $categoryObject = $categoriesRepository->findOneBy(array("slug" => $categoriaSlug));

        // Obtener Collection de productos de la categoría
        $productosCollectionObjects = $categoryObject->getProductos();

        // Obtener key del producto
        $productoKey = $productosCollectionObjects->indexOf($productoObject);

        // Obtener cantidad de productos
        $cantidadProductos = $productosCollectionObjects->count();

        // Obtener la key del siguiente producto
        $nextProductKey = $productoKey + 1;
        if($nextProductKey == $cantidadProductos) $nextProductKey = 0;

        // Obtener la key del producto pasado
        $pastProductKey = $productoKey - 1;
        if($pastProductKey < 0) $pastProductKey = $cantidadProductos - 1;

        // Obtener el objeto producto siguiente
        $nextProductObject = $productosCollectionObjects->get($nextProductKey);

        // Obtener el objeto producto pasado
        $pastProductObject = $productosCollectionObjects->get($pastProductKey);

        // Crear los links de los productos siguiente y pasado
        $nextProductLink = $this->generateURL('ag_principal_productos', array('categoriaSlug' => $categoriaSlug, 'productoSlug' => $nextProductObject->getSlug()));
        $pastProductLink = $this->generateURL('ag_principal_productos', array('categoriaSlug' => $categoriaSlug, 'productoSlug' => $pastProductObject->getSlug()));

        return $this->render("AGPrincipalBundle:Default:producto.html.twig", array(
            "producto" => $productoObject,
            "nextProductLink" => $nextProductLink,
            "pastProductLink" => $pastProductLink,
        ));
    }

    public function panelControlAction()
    {
        return $this->render('AGPrincipalBundle:Default:panelControl.html.twig');
    }

    public function catalogoAdminAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productosRepository = $em->getRepository('AGPrincipalBundle:Producto');
        $productosCollection = $productosRepository->findAll();

        return $this->render('AGPrincipalBundle:Default:catalogoAdmin.html.twig', array(
            "productos" =>  $productosCollection,
        ));
    }

    public function borrarProductoAction($productoId)
    {
        $em = $this->getDoctrine()->getManager();

        $productosRepository = $em->getRepository('AGPrincipalBundle:Producto');
        $productoObject = $productosRepository->find($productoId);

        $fotosRepository = $em->getRepository('AGPrincipalBundle:Foto');
        $fotosCollection = $fotosRepository->findBy(array('productoID' => $productoId));

        foreach ($fotosCollection as $key => $fotoObject) {
            $em->remove($fotoObject);
        }

        $em->remove($productoObject);
        $em->flush();

        // Se manda un mensaje de travesura realizada
        $this->get('session')->getFlashBag()->set(
            'notice',
            'Travesura realizada: El producto ('.$productoObject->getNombre().') ha sido eliminado de la base de datos.'
        );

        return $this->redirect($this->generateUrl('ag_principal_catalgo_admin'));
    }

    public function editarProductoAction(Request $request, $productoId)
    {
        $em = $this->getDoctrine()->getManager();
        $productosRepository = $em->getRepository('AGPrincipalBundle:Producto');
        $productoObject = $productosRepository->find($productoId);

        $formProducto = $this->createForm(new NuevoProductoType(), $productoObject);

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
                    $category = $categoryEntity->findOneById($formProducto['categoryID']->getData());

                    // Settear Categoría y Slug
                    $productoObject->setCategory($category);
                    $productoObject->setSlug($productoService->getSlug($productoObject->getNombre()));

                    // Persistir el nuevo producto y sus fotos
                    $em->persist($productoObject);
                    $em->flush();

                    // Obtener fotos y agregarlas a la base de datos
                    if($formProducto["Foto1"]->getData())
                    {
                        $nuevaFoto1 = new Foto();
                        $nuevaFoto1->setProducto($productoObject);
                        $nuevaFoto1->setFile($formProducto["Foto1"]->getData());
                        $nuevaFoto1->upload();
                        $em->persist($nuevaFoto1);
                    }
                    if($formProducto["Foto2"]->getData())
                    {
                        $nuevaFoto2 = new Foto();
                        $nuevaFoto2->setProducto($productoObject);
                        $nuevaFoto2->setFile($formProducto["Foto2"]->getData());
                        $nuevaFoto2->upload();
                        $em->persist($nuevaFoto2);
                    }
                    if($formProducto["Foto3"]->getData())
                    {
                        $nuevaFoto3 = new Foto();
                        $nuevaFoto3->setProducto($productoObject);
                        $nuevaFoto3->setFile($formProducto["Foto3"]->getData());
                        $nuevaFoto3->upload();
                        $em->persist($nuevaFoto3);
                    }

                    $em->flush();

                    // Se manda un mensaje de travesura realizada
                    $this->get('session')->getFlashBag()->set(
                        'notice',
                        'Travesura realizada: El producto ('.$productoObject->getNombre().') se editó con éxito en la base de datos.'
                    );

                    // Se redirecciona a la página de lista de productos
                    return $this->redirect($this->generateUrl('ag_principal_catalgo_admin'));
                }
            }
        }

        return $this->render('AGPrincipalBundle:Default:nuevoProducto.html.twig', array(
            'formProducto' => $formProducto->createView(),
            'fotos' => $productoObject->getFotos(),
        ));
    }

    public function borrarFotoAction($fotoId)
    {
        $em = $this->getDoctrine()->getManager();

        $fotosRepository = $em->getRepository('AGPrincipalBundle:Foto');
        $fotoObject = $fotosRepository->find($fotoId);

        $productoId = $fotoObject->getProductoID();

        $em->remove($fotoObject);
        $em->flush();

        // Se manda un mensaje de travesura realizada
        $this->get('session')->getFlashBag()->set(
            'notice',
            'Travesura realizada: Foto eliminada.'
        );

        return $this->redirect($this->generateUrl('ag_principal_editar_producto', array('productoId' => $productoId)));
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
                    $category = $categoryEntity->findOneById($formProducto['categoryID']->getData());

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
                        'Travesura realizada: El nuevo producto ('.$nuevoProducto->getNombre().') ha sido agregado a la base de datos.'
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