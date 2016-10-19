<?php
/**
 * Created by PhpStorm.
 * User: duviel
 * Date: 15/08/16
 * Time: 11:03 AM
 */

namespace Duvg\NoticiaBundle\Controller;

use Duvg\NoticiaBundle\Entity\Categorias;
use Duvg\NoticiaBundle\Entity\Noticia;
use Duvg\NoticiaBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

class NoticiaController extends FOSRestController
{

    /**
     * @Get("/createclient")
     */
    public function createTokenAction()
    {
        $clientManager = $this->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://localhost'));
        $client->setAllowedGrantTypes(array('token', 'authorization_code','password' ));
        $clientManager->updateClient($client);

        return new JsonResponse(array('estado'=>'echo'));
    }

    /**
     * return new for category and id
     * @Get("/categorias/{slug}/noticia/{id}")
     */
    public function getNoticiaAction($slug, $id)
    {
        $repository = $this->getDoctrine()->getRepository("NoticiaBundle:Noticia");
        $noticias = $repository->findBy(array('id'=>$id, 'categoria'=>$slug));

        $view = $this->view($noticias, 200);
        return $this->handleView($view);

    }

    /**
     * return all news
     * @Get("/noticias")
     */
    public function getNoticiasAction()
    {
        $repository = $this->getDoctrine()->getRepository("NoticiaBundle:Noticia");
        $noticias = $repository->findAll();

        $view = $this->view($noticias, 200);

        return $this->handleView($view);
    }


    /**
     * create a new news item by category
     * @Post("/categorias/{slug}/noticias/new")
     */
    public function newNoticiasAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $repoCategoria = $this->getDoctrine()->getRepository("NoticiaBundle:Categorias");

        $id = $slug;
        $categoria = $repoCategoria->find($id);

        $noticia = new Noticia();

        $noticia->setTitulo($request->get("titulo"));
        $noticia->setContenido($request->get("contenido"));
        $noticia->setCategoria($categoria);

        $em->persist($noticia);
        $em->flush();

        return new JsonResponse(1);
    }

    //custom post
    /**
     * create a new news item receive category in request
     * @Post("/noticias/news")
     */
    public function newNoticiaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repoCategoria = $this->getDoctrine()->getRepository("NoticiaBundle:Categorias");

        $categoria = $repoCategoria->find($request->get("categoria"));

        $noticia = new Noticia();

        $noticia->setTitulo($request->get("titulo"));
        $noticia->setContenido($request->get("contenido"));
        $noticia->setCategoria($categoria);

        $em->persist($noticia);
        $em->flush();
        $data = array("Estado"=>"Realizado");

        $view = $this->view($data,200);
        return $this->handleView($view);
    }

    //custom post
    /**
     * upload image the new
     * @Post("/noticias/newimage")
     */
    public function newNoticiaimgAction(Request $request)
    {
        $image = new Image();
        //$form = $this->createFormBuilder($image)->add('file')->add('ruta');
        //$form->handleRequest($request);
        //$name = $form['name']->getData();

        $em = $this->getDoctrine()->getManager();


//        $tipo = gettype($request->get('username'));
        //$image->upload();
        $dale1 = $request->get('name');
        $dale = $request->get('file');
        $mit = $request->files->get('file');
        //$mit = $this->getRequest()->files->get('file'); este funciona
        $datos = array('name' => $dale1, 'imagen'=> $mit);

        $image->setFile($mit);
        $image->upload();
        $em->persist($image);

        $em->flush();

        //return the response whit status ok
        $data = array("estado" => "realizado");
        $view = $this->view($data, 200);
        return $this->handleView($view);


    }

    /**
     * @Post("/noticias/newimage")
     */
    /*
    public function newImage(Request $request)
    {

        return 1;
        $em = $this->getDoctrine()->getManager();
        $image = new Image();
        $miFile = $this->serializar($request->get("image"));
        return new JsonResponse($miFile);

        $image->upload();
        $em->persist($image);

        $em->flush();


    }*/


    //Get for Doctrine Manager
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

}