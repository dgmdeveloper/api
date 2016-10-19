<?php
/**
 * Created by PhpStorm.
 * User: duviel
 * Date: 15/08/16
 * Time: 01:20 AM
 */

namespace Duvg\NoticiaBundle\Controller;


use Duvg\NoticiaBundle\Entity\Categorias;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Put;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

//import the entities

class CategoriasController extends FOSRestController
{

    //return specific category
    public function getCategoriaAction($slug)
    {
        $repository = $this->getDoctrine()->getRepository('NoticiaBundle:Categorias');

        $categorias = $repository->findBy(array('id'=>$slug));

        $view = $this->view($categorias,200);
        return $this->handleView($view);

    }


    //return all categories
    /**
     * @Get("/categorias")
     */
    public function getCategoriasAction()
    {
        $repository = $this->getDoctrine()->getRepository('NoticiaBundle:Categorias');

        $categorias = $repository->findAll();
        $view = $this->view($categorias, 200);

        return $this->handleView($view);
    }

    //register new category
    /**
     * @Post("newcategoria")
     */
    public function newCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoria = new Categorias();
        $categoria->setNombre($request->get('nombre'));
        $categoria->setDescripcion($request->get('descripcion'));

        $em->persist($categoria);

        $em->flush();

        $data = array("estado"=>"realizado");
        $view = $this->view($data, 200);

        return $this->handleView($view);
    }



    //Get for Doctrine Manager
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }
}