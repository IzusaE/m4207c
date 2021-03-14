<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Entity\Access;
use App\Entity\Autorisation;
use App\Entity\Document;
use App\Entity\Genre;


class ServeurController extends AbstractController
{
    // Page d'accueil de mon site
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }
    
    // Ici je vais venir mettre tout ce qui touche à des enregistrements de données

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
        //$hashpass = password_hash($recupPassword, PASSWORD_BCRYPT);
        //création d'un nouvel objet
		$utilisateur = new utilisateur();
		//insertion de la valeur dans l'objet
		$utilisateur->setNom($recupNom);
		$utilisateur->setPrenom($recupPrenom);
		$utilisateur->setEmail($recupEmail);
		//$utilisateur->setPassword($hashpass);
		$utilisateur->setPassword($recupPassword);
		$utilisateur->setCode('a');
		$utilisateur->setSalt('a');
		$utilisateur->setGroupeIdId('1');
		//Validation en BD
		$manager->persist($utilisateur);
		$manager->flush();
		return $this->redirectToRoute('serveur');
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
		return $this->redirectToRoute('listeGenre');
	}

    // Ici je vais venir mettre tout ce qui touche à des listes de données

       /**
    * @Route("/listeGenre", name="listeGenre")
    */
    public function listeGenre(EntityManagerInterface $manager, Request $request)
    {
        $listeGenre = $manager->getRepository(Genre::class)->findAll();
        return $this->render('serveur/listeGenre.html.twig', [
            'controller_name' => 'ServeurController',
            'listeGenre' => $listeGenre
        ]);
    }

    /**
    * @Route("/listeUser", name="listeUser")
    */
    public function listeUser(EntityManagerInterface $manager, Request $request)
    {
        $listeUser = $manager->getRepository(Utilisateur::class)->findAll();
        return $this->render('serveur/listeUser.html.twig', [
            'controller_name' => 'ServeurController',
            'listeUser' => $listeUser
        ]);
    }

    // Ici je vais mettre tout ce qui a un lien avec des comparaison de base de donnée

    /**
    * @Route("/delete/{id}", name="genre_delete")
    *
    */
    public function deleteGenre(EntityManagerInterface $manager, Request $request, Genre $genre)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();

        return $this->redirectToRoute('listeGenre');    
    }

    /**
    * @Route("/deleteUtilisateur/{id}", name="utilisateur_delete")
    *
    */
    public function deleteUtilisateur(EntityManagerInterface $manager, Request $request, Utilisateur $utilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($utilisateur);
        $em->flush();

        return $this->redirectToRoute('listeUser');    
    }

    /**
     * @Route("/loginUser", name="login_user")
     */
    public function loginUser(EntityManagerInterface $manager, Request $request)
    {
        $AllUsers = $manager->getRepository(Utilisateur::class)->findAll();
		$recupNom = $request->request->get("nom");
        $monNom = $recupNom;
        $recupPassword = $request->request->get("password");
        $user1 = $manager->getRepository(Utilisateur::class)->
        findBy( 
            array ('nom' => $recupNom, 'password' => $recupPassword) 
        );
        if ($user1 != NULL) {
            if ($monNom == 'admin') {
                return $this->redirectToRoute('confirmationConnectionadmin');
            } else {
                return $this->redirectToRoute('confirmationConnection');
            }
        } else {
            return $this->redirectToRoute('serveur');
        }
	}

    // Ici je vais venir mettre toutes mes pages autres

    /**
     * @Route("/confirmationConnection", name="confirmationConnection")
     */
    public function confirmationConnection()
    {
        return $this->render('serveur/confirmationConnection.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }

    /**
     * @Route("/confirmationConnectionadmin", name="confirmationConnectionadmin")
     */
    public function confirmationConnectionadmin()
    {
        return $this->render('serveur/confirmationConnectionadmin.html.twig', [
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
	
}
