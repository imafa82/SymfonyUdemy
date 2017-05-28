<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

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
    /**
     * @Route("/response", name="response")
     */
    public function responseAction(Request $request)
    {
        //$response = new Response('Contenuto', 200);
        //$response->send();
        $response = new Response();
        $response->setContent($this->render(':default:response.html.twig'));
        $response->setStatusCode(404);
        $response->setCharset('UTF-8');
        return $response;
        return $this->render('default/response.html.twig', array(), $response);
    }
    
    /**
     * @Route("/cookies", name="cookies")
     */
    public function cookiesAction(Request $request)
    {
        //Istanzio l'oggetto cookie
        $cookie = new Cookie('nomeCookie', 'ValoreCookie',(time()*3600*27*7));
        $response = new Response();
        $response->headers->setCookie($cookie);
        return $this->render('default/response.html.twig', [] , $response);
    }
     /**
     * @Route("/users/new", name="user_new")
     */
    public function createUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $fileName = $this->get('app.avatar_upload')->upload($user->getAvatar());
            $thumb = $this->get('app.avatar_resize')->resizeImage($fileName);
            $user->setAvatar($fileName);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('default/users.html.twig', array('form' => $form->createView()));
    }
}
