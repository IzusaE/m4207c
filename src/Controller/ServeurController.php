<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class ServeurController extends AbstractController
{
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }

    /**
     * @Route("/coco", name="coco")
     */
    public function coco()
    {
        return $this->render('serveur/coco.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }
    
    /**
    * @Route("/register", name="register")
    */
    public function register(EntityManagerInterface $manager, Request $request)
    {
        return $this->render('serveur/register.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }

	/**
     * @Route("/createUser", name="create_user")
     */
    public function createUser(EntityManagerInterface $manager, Request $request)
    {
		//Récupération des valeurs du formulaire
        $recupNom = $request->request->get("nom");
        $recupPrenom = $request->request->get("prenom");
        $recupEmail = $request->request->get("email");
        $recupPassword = $request->request->get("password");
        //création d'un nouvel objet
		$user = new User();
		//insertion de la valeur dans l'objet
		$user->setName($recupNom);
		$user->setPrenom($recupPrenom);
		$user->setEmail($recupEmail);
		$user->setPassword($recupPassword);
		//Validation en BD
		$manager->persist($user);
		$manager->flush();
		return $this->redirectToRoute('serveur');
	}

    /**
     * @Route("/loginUser", name="login_user")
     */
    public function loginUser(EntityManagerInterface $manager, Request $request)
    {
        $AllUsers = $manager->getRepository(User::class)->findAll();
		$recupNom = $request->request->get("nom");
        $recupPassword = $request->request->get("password");
        $user1 = $manager->getRepository(User::class)->
        findBy( 
            array ('prenom' => $recupNom, 'password' => $recupPassword) 
        );
        if ($user1 != NULL) {
            return $this->redirectToRoute('coco');
        } else {
            return $this->redirectToRoute('serveur');
        }
	}
    
}
