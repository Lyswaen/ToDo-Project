<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The HomeController class will only be called by the homepage.
 *
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * The index() method is used to render the homepage.
     *
     * It will be called in the first place as __it's *URL is "/"* which is the *root URL*__ of the website.
     *
     * @Route(path="/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }
}