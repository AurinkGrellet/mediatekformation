<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauxController
 *
 * @author aurink
 */
class AdminNiveauxController extends AbstractController {
    private const ADMINNIVEAUX = "admin/admin.niveaux.html.twig";
    private const ADMINNIVEAUXROUTE = "admin.niveaux";
    
    
    /**
     *
     * @var NiveauRepository
     */
    private $repository;
    
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    
    
    /**
     * 
     * @param NiveauRepository $repository
     */
    public function __construct(NiveauRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }    
    
    
    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render(self::ADMINNIVEAUX, [
            'niveaux' => $niveaux
        ]);
    }
    
    
    /**
     * @Route("admin/niveaux/suppr/{id}", name="admin.niveau.suppr")
     * @param Niveau $niveau
     * @return Response
     */
    public function suppr(Niveau $niveau, Request $request): Response{
        if($niveau->getFormations()->count() === 0 && $this->isCsrfTokenValid('suppr_niveau', $request->get('_token'))){
            $this->om->remove($niveau);
            $this->om->flush();
        }
        return $this->redirectToRoute(self::ADMINNIVEAUXROUTE);
    }
    
    
    /**
     * @Route("/admin/niveaux/ajout", name="admin.niveau.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        if($this->isCsrfTokenValid('ajout_niveau', $request->get('_token'))){
            $niveau = new Niveau();
            $nom = $request->get('nouveau_niveau');
            if (strlen($nom) > 15) {
                $nom = substr($nom, 0, 15);
            }
            $niveau->setLibelle($nom);
            $this->om->persist($niveau);
            $this->om->flush();
        }
        return $this->redirectToRoute(self::ADMINNIVEAUXROUTE);
    }
}
