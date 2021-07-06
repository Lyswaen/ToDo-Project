<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * The SecurityController is in charge of logging in and logging out the user.
 *
 * Class SecurityController
 * @package App\Controller
 * @Route(path="/user")
 */
class SecurityController extends AbstractController
{
    /**
     * The login() method is used to logged in the user when it will request it.
     *
     * It will be called in however methods the user use to __go on the *"/user/login"* URL__,
     * usually the user will just have to __click on the *"Se connecter"* link.__
     *
     * @Route(path="/login", name="user.login")
     * @param AuthenticationUtils $authenticationUtils
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error
            ]);
    }

    /**
     * The logout() method is used to logged out the user when it will request it.
     *
     * It will be called in however methods the user use to __go on the *"/user/logout"* URL__,
     * usually the user will just have to __click on the *"Se déconnecter"* link__.
     * Another information, is that __the logout() method's logic is normally never executed__,
     * actually __the logout() method is only here to specifying the route__ for it to be called,
     * the logic for logging out the user is done by the security service.
     *
     * @Route(path="/logout", name="user.logout")
     * @throw \LogicException
     * @return void
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
