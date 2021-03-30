<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
<<<<<<< HEAD
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Utilisateur;
use App\Entity\Access;
use App\Entity\Autorisation;
use App\Entity\Document;
=======
use App\Entity\User;
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
use App\Entity\Genre;


class ServeurController extends AbstractController
{
    // Page d'accueil de mon site
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {
        //$user1=$this->utilisateurConnecte($manager, $request, $session);

        $sess = $request->getSession();
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
    
    // Ici je vais venir mettre tout ce qui touche à des enregistrements de données

    /**
     * @Route("/createUser", name="create_user")
     */
    public function createUser(EntityManagerInterface $manager, Request $request)
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
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
            return $this->redirectToRoute('listeUser');
        } else {
            return $this->redirectToRoute('serveur');
        }
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
    public function listeUser(EntityManagerInterface $manager, Request $request, SessionInterface $session)
    {
        $sess = $request->getSession();
        if($sess->get("groupeUtilisateur") == 2) {
            $listeUser = $manager->getRepository(Utilisateur::class)->findAll();
            return $this->render('serveur/listeUser.html.twig', [
                'controller_name' => 'ServeurController',
                'listeUser' => $listeUser
            ]);
        } else {
            return $this->redirectToRoute('serveur');
        }
        //}
        //else
        //    return new Response ("Page réservé aux administrateurs");
    }

    /**
     * @Route("/listeFiles", name="listeFiles")
     */
	public function listeFiles(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
        //Requête qui récupère la liste des Users
        $listeFiles = $manager->getRepository(Access::class)->findBy(array('utilisateur_id_id' => ($sess->get("idUtilisateur"))));

        return $this->render('serveur/listeFiles.html.twig', [
            'controller_name' => "Liste des Documents",
            'listeFiles' => $listeFiles,
            'listeUsers' => $manager->getRepository(Utilisateur::class)->findAll(),
            'listeAutorisations' => $manager->getRepository(Autorisation::class)->findAll(),
            ]);
        }else{
        return $this->redirectToRoute('authentification');
        }
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
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute('listeUser');
        } else {
            return $this->redirectToRoute('serveur');   
        } 
    }

    /**
     * @Route("/deleteGed/{id}", name="deleteGed")
     */
    public function deleteGed(Request $request, EntityManagerInterface $manager, Document $id): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //il faut supprimer le lien dans access
            $recupListeAccess = $manager->getRepository(Access::class)->findByDocumentId($id);
            foreach($recupListeAccess as $doc){
            $manager->remove($doc);
            $manager->flush();
            }
            //supprimer le fichier du disuqe dur
            //suppression physique du document :
            if(unlink("upload/".$id->getChemin())){
                //suppression du lien dans la base de données
                $manager->remove($id);
                $manager->flush();
            }
            return $this->redirectToRoute('listeFiles');
        }else{
        return $this->redirectToRoute('serveur');
        }
    }

    /**
     * @Route("/loginUser", name="login_user")
     */
    public function loginUser(EntityManagerInterface $manager, Request $request, SessionInterface $session)
    {
        $AllUsers = $manager->getRepository(Utilisateur::class)->findAll();
		$recupNom = $request->request->get("nom");
        $recupPassword = $request->request->get("password");
        $monNom = $recupNom;
        $user1 = $manager->getRepository(Utilisateur::class)->
        findBy( 
            array ('nom' => $recupNom, 'password' => $recupPassword) 
        );
        //$userId=$session->get('userId');
        if ($user1 != NULL) {
            $utilisateur = new Utilisateur;
            $utilisateur = $user1[0];
            $sess = $request->getSession();
            $sess->set('idUtilisateur', $utilisateur->getId());
            $sess->set('nomUtilisateur', $utilisateur->getNom());
            $sess->set('groupeUtilisateur', $utilisateur->getGroupeIdId());
            $sess->set('prenomUtilisateur', $utilisateur->getPrenom());
            return $this->redirectToRoute('confirmationConnection');
        } else {
            return $this->redirectToRoute('serveur');
        }
        dd($recupNom, $recupPassword, $reponse);
        return new response(1);
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
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request, EntityManagerInterface $manager)
    {
        $sess = $request->getSession();
        $sess->remove("idUtilisateur");
        $sess->invalidate();
        $sess->clear();
        $sess=$request->getSession()->clear();
        return $this->redirectToRoute('serveur');
    }

    /**
    * @Route("/register", name="register")
    */
    public function register(EntityManagerInterface $manager, Request $request)
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            return $this->render('serveur/register.html.twig', [
                'controller_name' => 'New User',
            ]);
        } else {
            return $this->redirectToRoute('serveur');
        }
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
     * @Route("/registerFile", name="registerFile")
     */
    public function registerFile(Request $request, EntityManagerInterface $manager): Response
    {
<<<<<<< HEAD
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
        //création d'un nouveau document
        $Document = new Document();
        //Récupération et transfert du fichier
        //dd($request->request->get('choix'));
        $brochureFile = $request->files->get("fichier");
        if ($brochureFile){
        $newFilename = uniqid('', true) . "." . $brochureFile->getClientOriginalExtension();
        $brochureFile->move($this->getParameter('upload'), $newFilename);
        //insertion du document dans la base.
        if($request->request->get('choix') == "on"){
        $actif=1;
        }else{
        $actif=2;
        }
        $Document->setActif($actif);
        $Document->setNom($request->request->get('nom'));
        $Document->setTypeId($manager->getRepository(Genre::class)->findOneById($request->request->get('genre')));
        $Document->setCreatedAt(new \Datetime);
        $Document->setChemin($newFilename);

        $manager->persist($Document);
        $manager->flush();
        }
        //Création d'un access pour la personne avec laquelle on veut partager le document
        if($request->request->get('utilisateur') != -1){
        $user = $manager->getRepository(Utilisateur::class)->findOneById($request->request->get('utilisateur'));
        $autorisation = $manager->getRepository(Autorisation::class)->findOneById($request->request->get('autorisation'));
        $access = new Access();
        $access->setUtilisateurIdId($user);
        $access->setAutorisationId($autorisation);
        $access->setDocumentId($Document);
        $manager->persist($access);
        $manager->flush();
        }
        //Création d'un accès pour l'uploadeur (propriétaire)
        $user = $manager->getRepository(Utilisateur::class)->findOneById($sess->get("idUtilisateur"));
        $autorisation = $manager->getRepository(Autorisation::class)->findOneById(1);
        $access = new Access();
        $access->setUtilisateurIdId($user);
        $access->setAutorisationId($autorisation);
        $access->setDocumentId($Document);
        $manager->persist($access);
        $manager->flush();


        return $this->redirectToRoute('listeFiles');
        }else{
        return $this->redirectToRoute('serveur');
        }
    }

    /**
     * @Route("/newFile", name="newFile")
     */
    public function newFile(Request $request, EntityManagerInterface $manager): Response
    {
        //Requête pour récupérer toute la table genre
        $listeGenre = $manager->getRepository(Genre::class)->findAll();
        return $this->render('serveur/newFile.html.twig', [
        'controller_name' => "Upload d'un Document",
        'listeGenre' => $listeGenre,
        'listeUsers' => $manager->getRepository(Utilisateur::class)->findAll(),
        'listeAutorisation' => $manager->getRepository(Autorisation::class)->findAll(),
        ]);
    }

    /**
     * @Route("partageFile", name="partageFile")
     */
    public function partageFile(Request $request, EntityManagerInterface $manager): Response
    {
    $sess = $request->getSession();
    if($sess->get("idUtilisateur")){
        //Requête le user en focntion du formulaire
        $user = $manager->getRepository(Utilisateur::class)->findOneById($request->request->get('utilisateur'));
        $autorisation = $manager->getRepository(Autorisation::class)->findOneById($request->request->get('autorisation'));
        $document = $manager->getRepository(Document::class)->findOneById($request->request->get('doc'));
        $access = new Access();
        $access->setUtilisateurIdId($user);
        $access->setAutorisationId($autorisation);
        $access->setDocumentId($document);
        $manager->persist($access);
        $manager->flush();

        return $this->redirectToRoute('listeFiles');
    }else{
    return $this->redirectToRoute('serveur');
    }
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
	public function dashboard(Request $request, EntityManagerInterface $manager): Response
	{
		{
			$sess = $request->getSession();
            if($sess->get("idUtilisateur")){
                //*******************Requetes Mysql*******************
                //Récupération du nombre de document
                $listeDocuments = $manager->getRepository(Access::class)->findBy(array('utilisateur_id_id' => ($sess->get("idUtilisateur"))));
                $listeDocumentAll = $manager->getRepository(Access::class)->findAll(); 
                $listeUsers = $manager->getRepository(Utilisateur::class)->findAll();
                $listeAutorisations = $manager->getRepository(Autorisation::class)->findAll();
                //*********************Variables*********************
                $flag = 0 ; //indique que le document privé
                $nbDocument = 0;
                $nbDocumentPrives = 0;
                $documentPrives = Array();
                $lastDocument = new \Datetime("2000-01-01");
                
                foreach($listeDocuments as $val){
                    $nbDocument++;	
                    $document = $val->getDocumentId()->getId();
                    if($val->getDocumentId()->getCreatedAt() > $lastDocument){
                        $lastDocument = $val->getDocumentId()->getCreatedAt();
                        $documentDate = $val->getDocumentId();
                        
                    }
                    foreach($listeDocumentAll as $val2){
                        if($val2->getDocumentId()->getId() == $document && $val2->getUtilisateurIdId()->getId() != $sess->get("idUtilisateur") )
                            $flag++;	
                    }
                    if($flag == 0){
                        $documentPrives[] = $val ;
                        $nbDocumentPrives ++;
                    }
                    $flag =0;
                }
                return $this->render('serveur/dash.html.twig',[
                'controller_name' => "Espace Client",
                'nb_document' => $nbDocument,
                'listeDocumentPrives' => $documentPrives,
                'nbDocumentPrives' => $nbDocumentPrives,
                'listeUsers' => $listeUsers,
                'listeAutorisations' => $listeAutorisations,
                //'documentDate' => $documentDate,
                ]);
            }else{
                return $this->redirectToRoute('serveur');
            }
		}
=======
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
>>>>>>> 2b7330020022ea2defc1d5ad64398e0f20e8c6cf
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
