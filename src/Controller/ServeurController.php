<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use DateTime;
use App\Entity\Utilisateur;
use App\Entity\Access;
use App\Entity\Autorisation;
use App\Entity\Document;
use App\Entity\User;
use App\Entity\Genre;


class ServeurController extends AbstractController
{
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {
        // Je viens démarrer ma session
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")) {
            return $this->redirectToRoute('confirmationConnection');
        } else {
            return $this->render('serveur/index.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        }
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
            $recupGroupe = $request->request->get("groupe");
            $hashpass = password_hash($recupPassword, PASSWORD_DEFAULT);
            //création d'un nouvel objet
            $utilisateur = new utilisateur();
            //insertion de la valeur dans l'objet
            $utilisateur->setNom($recupNom);
            $utilisateur->setPrenom($recupPrenom);
            $utilisateur->setEmail($recupEmail);
            $utilisateur->setPassword($hashpass);
            //$utilisateur->setPassword($recupPassword);
            $utilisateur->setCode('a');
            $utilisateur->setSalt('a');
            $utilisateur->setGroupeIdId($recupGroupe);
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



    /**
     * @Route("/registerFile", name="registerFile")
     */
    public function registerFile(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            $chemin = '/var/www/html/symfony/m4207c/public';
            $nom = $_FILES['fichier']['name'];
            $nomtmp = $_FILES['fichier']['tmp_name'];
            $dest = $chemin.'//'.basename($_FILES['fichier']['name']);
            $resultat= move_uploaded_file($_FILES['fichier']['tmp_name'],$dest);
            if($request->request->get('choix') == "on"){
                $actif=1;
            } else {
                $actif=2;
            }

            $Document = new Document();
            //$MyTpeIpd = $manager->getRepository(Genre::class)->findByDocumentId($id);
            $Document->setTypeId($manager->getRepository(Genre::class)->findOneById($_POST['genre']));
            $Document->setChemin($dest);
            $Document->setNomDoc($request->request->get("nom"));
            $date = new \DateTime('NOW');
            $Document->setDate($date);
            $Document->setActif($actif);
            $manager->persist($Document);
            $manager->flush();

            if ($request->request->get('utilisateur') != -1) {
                $user = $manager->getRepository(Utilisateur::class)->findOneById($request->request->get('utilisateur'));
                //dd($user);
                $autorisation = $manager->getRepository(Autorisation::Class)->findOneById(1);
                $access = new Access();
                $access->setUtilisateurId($user);
                //dd($access->setUtilisateurId($user));
                $access->setAutorisationId($autorisation);
                $access->setDocumentId($Document);
                $manager->persist($access);
                $manager->flush();
            }

            $user = $manager->getRepository(Utilisateur::class)->findOneById($sess->get("idUtilisateur"));
            $autorisation = $manager->getRepository(Autorisation::class)->findOneById(1);
            $access = new Access();
            $access->setUtilisateurId($user);
            $access->setAutorisationId($autorisation);
            $access->setDocumentId($Document);
            $manager->persist($access);
            $manager->flush();

            return $this->redirectToRoute('listeFiles');
        } else {
            return $this->redirectToRoute('serveur');
        }
    }

    // Ici je vais mettre tout ce qui a un lien avec des comparaison de base de donnée

    /**
    * @Route("/delete/{id}", name="genre_delete")
    *
    */
    public function deleteGenre(EntityManagerInterface $manager, Request $request, Genre $genre)
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            $em = $this->getDoctrine()->getManager();
            $em->remove($genre);
            $em->flush();

            return $this->redirectToRoute('listeGenre');    
        } else {
            return $this->redirectToRoute('serveur');   
        } 
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
    public function loginUser(EntityManagerInterface $manager, Request $request, SessionInterface $sess)
    {
        $AllUsers = $manager->getRepository(Utilisateur::class)->findAll();
		$recupNom = $request->request->get("nom");
        $recupPassword = $request->request->get("password");
        $user1 = $manager->getRepository(Utilisateur::class)->findOneBy(array('nom' => $recupNom));
        $recupPassUse = $user1->getPassword();
        $Verification = password_verify($recupPassword, $recupPassUse); 
        if ($Verification == TRUE) {
            $sess->set('idUtilisateur', $user1->getId());
            $sess->set('nomUtilisateur', $user1->getNom());
            $sess->set('groupeUtilisateur', $user1->getGroupeIdId());
            $sess->set('prenomUtilisateur', $user1->getPrenom());
            return $this->redirectToRoute('confirmationConnection');
        } else {
            return $this->redirectToRoute('serveur');
        }
        dd($recupNom, $recupPassword, $reponse);
        return new response(1);
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

    // Ici je vais venir mettre toutes mes pages autres

    /**
     * @Route("/confirmationConnection", name="confirmationConnection")
     */
    public function confirmationConnection(Request $request, EntityManagerInterface $manager)
    {
        // Page pour la réussite de la connexion
        $sess = $request->getSession();
        $myname = $sess->get("nomUtilisateur");
        return $this->render('serveur/confirmationConnection.html.twig', [
            'controller_name' => 'ServeurController',
            'nomSession' => $myname
        ]);
    }

    /**
    * @Route("/register", name="register")
    */
    public function register(EntityManagerInterface $manager, Request $request)
    {
        $sess = $request->getSession();
        if($sess->get("groupeUtilisateur") == 2) {
            return $this->render('serveur/register.html.twig', [
                'controller_name' => 'New User',
            ]);
        } else if ($sess->get("idUtilisateur")) {
            return $this->redirectToRoute('errorSess');
        } else {
            return $this->redirectToRoute('serveur');
        }
    }

    /**
    * @Route("/registerGenre", name="registerGenre")
    */
    public function registerGenre(EntityManagerInterface $manager, Request $request)
    {
        $sess = $request->getSession();
        if($sess->get("groupeUtilisateur") == 2) {
            return $this->render('serveur/registerGenre.html.twig', [
                'controller_name' => 'ServeurController',
            ]);
        } else if ($sess->get("idUtilisateur")) {
            return $this->redirectToRoute('errorSess');
        } else {
            return $this->redirectToRoute('serveur');
        }
    }

    /**
     * @Route("/newFile", name="newFile")
     */
    public function newFile(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Requête pour récupérer toute la table genre
            $listeGenre = $manager->getRepository(Genre::class)->findAll();
            $listeUtilisateurs = $manager->getRepository(Utilisateur::class)->findAll();
            $listeAutorisations = $manager->getRepository(Autorisation::class)->findAll();
            return $this->render('serveur/newFile.html.twig', [
            'controller_name' => "Upload d'un Document",
            'listeGenre' => $listeGenre,
            'listeUsers' => $listeUtilisateurs,
            'listeAutorisation' => $listeAutorisations
            ]);
        } else {
            return $this->redirectToRoute('serveur');
        }
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
        } else if ($sess->get("idUtilisateur")) {
            return $this->redirectToRoute('errorSess');
        } else {
            return $this->redirectToRoute('serveur');
        }
        //}
        //else
        //    return new Response ("Page réservé aux administrateurs");
    }
    
    /**
    * @Route("/listeGenre", name="listeGenre")
    */
    public function listeGenre(EntityManagerInterface $manager, Request $request)
    {
        $sess = $request->getSession();
        if($sess->get("groupeUtilisateur") == 2) {
            $listeGenre = $manager->getRepository(Genre::class)->findAll();
            return $this->render('serveur/listeGenre.html.twig', [
                'controller_name' => 'ServeurController',
                'listeGenre' => $listeGenre
            ]);
        } else if ($sess->get("idUtilisateur")) {
            return $this->redirectToRoute('errorSess');
        } else {
            return $this->redirectToRoute('serveur');
        }
    }


    /**
     * @Route("/listeFiles", name="listeFiles")
     */
	public function listeFiles(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Requête qui récupère la liste des Users
            $listeFiles = $manager->getRepository(Access::class)->findByUtilisateurId($sess->get("idUtilisateur"));

            return $this->render('serveur/listeFiles.html.twig', [
                'controller_name' => "Liste des Documents",
                'listeFiles' => $listeFiles,
                'listeUsers' => $manager->getRepository(Utilisateur::class)->findAll(),
                'listeAutorisations' => $manager->getRepository(Autorisation::class)->findAll(),
                ]);
        } else {
            return $this->redirectToRoute('serveur');
        }
    }

    /**
     * @Route ("/permission", name="permission")
     */
    public function permission(Request $request, EntityManagerInterface $manager, Document $id): Response
    {
		$sess = $request->getSession();
		if($sess->get("idUtilisateur")){
			//Récupération des listes
			$listeDocument = $manager->getRepository(Document::class)->findAll();
			$listeUser = $manager->getRepository(Utilisateur::class)->findAll();
			return $this->render('ged/permission.html.twig', [
            'controller_name' => "Attribution d'une permission",
            'listeDocument' => $listeDocument,
            'listeUser' => $listeUser,
        ]);
		}else{
			return $this->redirectToRoute('serveur');	
		}
    }

    /**
     * @Route("partageGed", name="partageGed")
     */
    public function partageGed(Request $request, EntityManagerInterface $manager): Response
    {
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
            //Requête le user en focntion du formulaire
            $user = $manager->getRepository(Utilisateur::class)->findOneById($request->request->get('utilisateur'));
            $autorisation = $manager->getRepository(Autorisation::class)->findOneById($request->request->get('autorisation'));
            $document = $manager->getRepository(Document::class)->findOneById($request->request->get('doc'));
            $access = new Access();
            $access->setUtilisateurId($user);
            $access->setAutorisationId($autorisation);
            $access->setDocumentId($document);
            $manager->persist($access);
            $manager->flush();

            return $this->redirectToRoute('listeFiles');
        } else {
            return $this->redirectToRoute('serveur');
        }
    }

    public function uploadGed(Request $request, EntityManagerInterface $manager): Response
    {
        //Requête pour récupérer toute la table genre
        $listeGenre = $manager->getRepository(Genre::class)->findAll();
        return $this->render('ged/uploadGed.html.twig', [
        'controller_name' => "Upload d'un Document",
        'listeGenre' => $listeGenre,
        'listeUsers' => $manager->getRepository(Utilisateur::class)->findAll(),
        'listeAutorisation' => $manager->getRepository(Autorisation::class)->findAll(),
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
	public function dashboard(Request $request, EntityManagerInterface $manager): Response
	{
        $sess = $request->getSession();
        if($sess->get("idUtilisateur")){
			//*******************Requetes Mysql*******************
			//Récupération du nombre de document
			$listeDocuments = $manager->getRepository(Access::class)->findByUtilisateurId($sess->get("idUtilisateur"));
            $listeFiles = $manager->getRepository(Access::class)->findByUtilisateurId($sess->get("idUtilisateur"));
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
				foreach($listeDocumentAll as $val2){
					if($val2->getDocumentId()->getId() == $document && $val2->getUtilisateurId()->getId() != $sess->get("idUtilisateur") )
						$flag++;	
				}
				if($flag == 0){
					$documentPrives[] = $val ;
					$nbDocumentPrives ++;
				}
				$flag =0;
			}
			return $this->render('serveur/dashboard.html.twig',[
			 'controller_name' => "Espace Client",
			 'nb_document' => $nbDocument,
			 'listeDocumentPrives' => $documentPrives,
			 'nbDocumentPrives' => $nbDocumentPrives,
			 'listeUsers' => $listeUsers,
			 'listeAutorisations' => $listeAutorisations,
                'listeFiles' => $listeFiles,
			 //'documentDate' => $documentDate,
			 ]);
		}else{
			return $this->redirectToRoute('serveur');
		}
	}

    /**
    * @Route("/errorSess", name="errorSess")
    */
    public function errorSess(EntityManagerInterface $manager, Request $request)
    {
        return $this->render('serveur/errorSess.html.twig', [
            'controller_name' => 'New User',
        ]);
    }
    
}
