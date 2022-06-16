<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends Controller
{
    /**
     * @Route("/blog/{page}", name="blog_list", requirements={"page"="\d+"})
     */
    public function listAction($page = 1)
    {
        # code...
    }

    /**
     * Matches /blog/*
     * but not /blog/slug/extra-part
     *
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function FunctionNamshowAction($slug)
    {
        # code...
    }
}