<?php
namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;

/**
 * Description of AdminFormationsController
 *
 * @author aurink
 */
class AdminFormationsController extends AbstractController {
    
    private const PAGEFORMATIONS = "admin/admin.formations.html.twig";
    
    
    /**
     *
     * @var FormationRepository
     */
    private $repository;
    
    
     /**
     * 
     * @param NiveauRepository $repositoryNiveau
     */
    private $repositoryNiveau;
    
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository, NiveauRepository $repositoryNiveau) {
        $this->repository = $repository;
        $this->repositoryNiveau = $repositoryNiveau;
    }    
    
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repositoryNiveau->findAll();
        $formations = $this->repository->findAll();
        return $this->render(self::PAGEFORMATIONS, [
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
        return $this->render(self::PAGEFORMATIONS, [
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
            return $this->render(self::PAGEFORMATIONS, [
                'formations' => $formations,
                'niveaux' => $niveaux,
                'niveauselection' => $valeur
            ]);
        }
        return $this->redirectToRoute("formations");
    }
}
