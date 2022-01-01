<?php
namespace App\Controller\admin;

use DateTimeImmutable;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author aurink
 */
class AdminFormationsController extends AbstractController {
    
    private const ADMINFORMATIONS = "admin/admin.formations.html.twig";
    private const ADMINFORMATIONSROUTE = "admin.formations";
    
    
    /**
     *
     * @var FormationRepository
     */
    private $repository;
    
    
     /**
     * 
     * @var NiveauRepository $repositoryNiveau
     */
    private $repositoryNiveau;
    
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(EntityManagerInterface $om, FormationRepository $repository, NiveauRepository $repositoryNiveau) {
        $this->repository = $repository;
        $this->repositoryNiveau = $repositoryNiveau;
        $this->om = $om;
    }    
    
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repositoryNiveau->findAll();
        $formations = $this->repository->findAll();
        return $this->render(self::ADMINFORMATIONS, [
            'formations' => $formations,
            'niveaux' => $niveaux
        ]);
    }
    
    
    /**
     * @Route("admin/formations/tri/{champ}/{ordre}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $niveaux = $this->repositoryNiveau->findAll();
        $formations = $this->repository->findAllOrderBy($champ, $ordre);
        return $this->render(self::ADMINFORMATIONS, [
           'formations' => $formations,
           'niveaux' => $niveaux
        ]);
    }  
    
        
    /**
     * @Route("admin/formations/recherche/{champ}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response{
        if($this->isCsrfTokenValid('filtre_'.$champ, $request->get('_token'))){
            $niveaux = $this->repositoryNiveau->findAll();
            $valeur = $request->get("recherche");
            if ($champ == "niveau") {
                $formations = $this->repository->findByLevel("libelle", $valeur);
            }
            else {
                $formations = $this->repository->findByContainValue($champ, $valeur);
            }
            return $this->render(self::ADMINFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux,
                'niveauselection' => $valeur
            ]);
        }
        return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
    }
    
    
    /**
     * @Route("admin/formations/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation, Request $request): Response{
        if($this->isCsrfTokenValid('suppr_formation', $request->get('_token'))){
            $this->om->remove($formation);
            $this->om->flush();
        }
        return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
    }
    
    
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @return Response
     */
    public function edit(Formation $formation, Request $request): Response {
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->flush();
            return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
        }
        if($this->isCsrfTokenValid('edit_formation', $request->get('_token'))){
            return $this->render("admin/admin.formation.edit.html.twig", [
                'requete' => 'edit',
                'formation' => $formation,
                'formformation' => $formFormation->createView()
            ]);
        }
        return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
    }
    
    
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTimeImmutable());
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->om->persist($formation);
            $this->om->flush();
            return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
        }
        if($this->isCsrfTokenValid('ajout_formation', $request->get('_token'))){
            return $this->render("admin/admin.formation.ajout.html.twig", [
                'requete' => 'ajout',
                'formformation' => $formFormation->createView()
            ]);
        }
        return $this->redirectToRoute(self::ADMINFORMATIONSROUTE);
    }
}
