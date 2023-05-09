<?php

namespace App\Controller;
use App\Entity\Crud;
use App\Form\CrudType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\EntityManager; 

class MainController extends AbstractController
{private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        $data = $this->entityManager->getRepository(Crud::class)->findAll();
         
        return $this->render('main/index.html.twig', [
            'data' => $data,
        ]);
    }
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $crude = new Crud();
        $form = $this->createForm(CrudType::class, $crude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($crude);
            $this->entityManager->flush();
            $this->addFlash('rotice', 'Submitted Successfully');
            return $this->redirectToRoute('main'); 
        }
    
        return $this->render('main/create.html.twig', [
            'form' => $form->CreateView()
        ]);
    }
     #[Route('/main', name: 'main')]
    #[Route("/update/{id}", name: "update")]
    public function update(Request $request,$id): Response
    {
        $crude = $this->entityManager->getRepository(Crud::class)->find($id);
        $form = $this->createForm(CrudType::class, $crude);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($crude);
            $this->entityManager->flush();
            //$this->addFlash('rotice', 'Update Successfully');
            return $this->redirectToRoute('main');
        }
        return $this->render('main/update.html.twig', [
            'form' => $form->CreateView()
        ]);
    
}
#[Route("/delete/{id}", name: "delete")]
public function delete($id): Response
{
    $data = $this->entityManager->getRepository(Crud::class)->find($id);
    $this->entityManager->remove($data);
    $this->entityManager->flush();
        $this->addFlash('rotice', 'Update Successfully');
        return $this->redirectToRoute('main'); 
    }
   


}

   