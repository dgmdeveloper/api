<?php
/**
 * Created by Duviel Garcia.
 * User: duviel
 * Date: 14/08/16
 * Time: 05:00 PM
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
use FOS\RestBundle\Controller\Annotations\Put;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints\DateTime;

class UserController extends FOSRestController
{
    
    /**
     * @Get("/user")
     */
    public function getUsersAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $this->serializar($userManager->findUsers());

        $view = $this->view($users, 200);
        return $this->handleView($view);        
    }

    /**
     * @Get("/user/{user}")
     */
    public function getUserAction($user)
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $this->serializar($userManager->findUserByUsername($user));

        //query
        $em = $this->getDoctrineManager();
        $query = $em->createQuery('SELECT m FROM ApiBundle:User m WHERE m.username = :username');
        $query->setParameter('username',$user);
        $dat = $this->serializar($query->getResult());
        return new Response($dat);
    }

    //actualizacion del usuario
    /**
     * PUT Route annotation.
     * @Put("/user/{id}")
     */
    public function putUserAction(Request $request, $id)
    {
        $em = $this->getDoctrineManager();

        $userManager = $this->get('fos_user.user_manager');

        //take id from request
        $id = $request->get('id');

        //return new JsonResponse($request->get('username'));

        $user = $userManager->findUserBy(array('id' => $id));
        $user->setUsername($request->get('username'));
        $user->setPlainPassword($request->get('password'));
        $user->setEmail($request->get('email'));
        $user->setCedula($request->get('cedula'));
        $user->setNombre($request->get('nombre'));
        $user->setApellidos($request->get('apellidos'));
        $user->setDireccion($request->get('direccion'));
        $user->setTelefono($request->get('telefono'));
        $user->setEnabled(true);
        $userManager->updateUser($user, true);
        $em->flush();


        return new JsonResponse($user);
    }

    //registrar un nuevo usuario


    /**
     * POST Route annotation.
     * @Post("/user")
     */
    public function newuserAction(Request $request)
    {

        $cadena = "cadena de texto numero1";
        $datos = $request->get('nombre').$request->get('apellidos').$request->get('direccion').$request->get('telefono').$request->get('email');
 
         //$view = $this->view($datos, 200);
            //return $this->handleView($view);   

        //return new JsonResponse($request);

        try {
            $em = $this->getDoctrineManager();

            //create user
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->createUser();
            $user->setCedula($request->get('cedula'));
            $user->setNombre($request->get('nombre'));
            $user->setApellidos($request->get('apellidos'));
            $user->setDireccion($request->get('direccion'));
            $user->setTelefono($request->get('telefono'));
            $user->setEmail($request->get('email'));
            $user->setFechaNacimiento(new \DateTime($request->get('fechaNacimiento')));
            $user->setEnabled(true);
            $datos = $request->get('cedula');

            
            //$user->setTelefono($request->get('telefono'));
            $user->setUsername($request->get('username'));
            $user->setPlainPassword($request->get('password'));

            $userManager->updateUser($user, true);
            $respuesta = array('estado' => '1');

            $view = $this->view($datos, 200);
            return $this->handleView($view);
        } catch (Exception $e) {
            $respuesta = array('estado' => '0');
            return new JsonResponse($respuesta);
        }

    }

    //serializador de objetos
    public function serializar($data)
    {
        $serializerjms = $this->get('jms_serializer');
        return $serializerjms->serialize($data, 'json');
    }

    //Get for Doctrine Manager
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    

}