<?php

namespace Duvg\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PruebaController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }
}
