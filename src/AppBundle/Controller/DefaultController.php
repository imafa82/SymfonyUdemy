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
     * @Route("/nome/{nome}", name="test")
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
    
    /**
     * @Route("/nominativo", name="test2")
     * Method("POST")
     */
    
    public function test2Action(Request $request)
    {
        echo $request->request->getAlpha('nome');
        echo "<br>";
        echo $request->request->getDigits('nome');
        $nome = $request->request->get('nome');
        $file =  $request->files->get('file')?:null;
        if($file)
        var_dump($file);
        
        return $this->render('default/nome.html.twig', [
            'nome' => $nome
        ]);
    }
    /**
     * @Route("/test/{nome}", name="test3")
     */
    public function test3Action(Request $request, $nome)
    {
        //Verifica se l'url ha o no cognome come variabile
        if($request->query->has('cognome')){
            echo "cognome c'è";
            echo $request->get('cognome');
            echo $request->query->get('cognome');
        } else {
            echo "mi dispiace ma cognome non c'è";
        }
        
        $request->query->add(array('citta' => 'Torino'));
        if($request->query->has('citta')){
            echo $request->query->get('citta');
        }
        $request->query->remove('citta');
        $request->query->replace(array('cognome' => 'Salernino'));
        echo "<br>";
        var_dump($request->query);
        echo "<br>";
        //var_dump($request);
        echo $request->headers->get('user-agent');
        return $this->render('default/request.html.twig', [
            'nome' => $nome, 'res' => $request
        ]);
    }
}
