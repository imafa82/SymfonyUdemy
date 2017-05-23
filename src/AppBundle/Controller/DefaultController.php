<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        echo $request->getBaseUrl();
        echo $request->getRequestUri();
        echo  $request->getMethod();
        echo $request->getDefaultLocale();
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
    
    /**
     * @Route("/{nome}", name="test")
     */
    public function testAction(Request $request, $nome)
    {
        echo $nome;
        echo "<br>";
        echo $request->get('nome');
        echo "<br>";
        echo $request->query->get('citta');
        echo "<br>";
        echo $request->get('citta');
        echo "<br>";
        echo $request->server->get('QUERY_STRING');
        echo "<br>";
        echo $request->attributes->get('nome');
        echo "<br>";
        echo $request->attributes->get('_route_params')['nome'];
        // replace this example code with whatever you need
        return $this->render('default/nome.html.twig', [
            'nome' => $nome
        ]);
    }
}
