<?php

/**
 * Created by Duviel Garcia.
 * User: duviel
 * Date: 15/08/16
 * Time: 12:52 AM
 */

namespace Duvg\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

//call entities reference
use Duvg\ApiBundle\Entity\Test;



class TestController extends FOSRestController
{	

	/**
     * @Get("/test")
     */
    public function getTestAction()
    {
        $repository = $this->getDoctrine()->getRepository("ApiBundle:Test");
        $tests = $repository->findAll();

        $view = $this->view($tests, 200);
        return $this->handleView($view);        
    }

    /**
     * POST Route annotation
     * @Post("/test")
     */
    public function newTestAction(Request $request)
    {
        
    	$response = $request->get('nombre');
    	$em = $this->getDoctrine()->getManager();

    	$test = new Test();
    	$test->setNombre($response);

    	$em->persist($test);
        $em->flush();


    	$view = $this->view("Realizado", 200);
        return $this->handleView($view);        
    }



}