<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Genre;


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
    * @Route("/registerGenre", name="registerGenre")
    */
    public function registerGenre(EntityManagerInterface $manager, Request $request)
    {
        return $this->render('serveur/registerGenre.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }
    
    /**
    * @Route("/listeUser", name="listeUser")
    */
    public function listeUser(EntityManagerInterface $manager, Request $request)
    {
        $listeUser = $manager->getRepository(User::class)->findAll();
        return $this->render('serveur/listeUser.html.twig', [
            'controller_name' => 'ServeurController',
            'listeUser' => $listeUser
        ]);
    }
    
    /**
    * @Route("/listGenre", name="listGenre")
    */
    public function listGenre(EntityManagerInterface $manager, Request $request)
    {
        $listeGenre = $manager->getRepository(Genre::class)->findAll();
        return $this->render('serveur/listGenre.html.twig', [
            'controller_name' => 'ServeurController',
            'listeGenre' => $listeGenre
        ]);
    }

    
    /**
    * @Route("/delete/{id}", name="genre_delete")
    *
    * @return Response
    */
    public function deleteGenre(EntityManagerInterface $manager, Request $request, Genre $genre)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();

        return $this->redirectToRoute('listGenre');    
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
		return $this->redirectToRoute('listeUser');
	}

	/**
     * @Route("/createGenre", name="createGenre")
     */
    public function createGenre(EntityManagerInterface $manager, Request $request)
    {
		//Récupération des valeurs du formulaire
        $recupType = $request->request->get("type");
        //création d'un nouvel objet
		$genre = new Genre();
		//insertion de la valeur dans l'objet
		$genre->setType($recupType);
		//Validation en BD
		$manager->persist($genre);
		$manager->flush();
		return $this->redirectToRoute('listGenre');
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
