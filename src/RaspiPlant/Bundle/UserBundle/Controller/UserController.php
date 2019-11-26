<?php

namespace RaspiPlant\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;

class UserController extends BaseController
{
    public function renderLogin(array $data)
    {
        return $this->render('@UserBundle/Resources/views/Security/login.html.twig', $data);
    }
}
