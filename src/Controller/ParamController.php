<?php

namespace App\Controller;

use App\Repository\ParametreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParamController extends AbstractController
{
    private $param;
    private $em;
    public function __construct(ParametreRepository $param,EntityManagerInterface $em)
    {
        $this->param = $param;
        $this->em = $em;
    }
    #[Route('/', name: 'app_param')]
    public function index(): Response
    {
       $param = $this->param->findBy([],["position"=>"ASC"]);
        return $this->render('param/index.html.twig', [
            'param' => $param,
        ]);
    }

     #[Route('/newposition', name: 'app_new')]
    public function newposition(Request $request): Response
    {
       if($request->isXmlHttpRequest()){
        $positions = $request->get('positions');
         foreach($positions as $position) {
            $index = $position[0];
            $parametre = $this->param->findOneBy(["id"=>$index]);
            $parametre->setPosition($position[1]);
            $this->em->persist($parametre);
            $this->em->flush();
            $response = $this->json(['status'=>$position[0]],
            200,
            ['Content-Type' => 'appication/json']
        );
         }   
       }else{
        return false;
       }
      
    return $response;
    }
}
