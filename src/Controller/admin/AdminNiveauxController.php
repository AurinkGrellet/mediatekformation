<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\NiveauRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminNiveauxController
 *
 * @author aurink
 */
class AdminNiveauxController extends AbstractController {
    private const PAGENIVEAUX = "admin/admin.niveaux.html.twig";
    
    
    /**
     *
     * @var NiveauRepository
     */
    private $repository;
    
    
    /**
     * 
     * @param NiveauRepository $repository
     */
    public function __construct(NiveauRepository $repository) {
        $this->repository = $repository;
    }    
    
    
    /**
     * @Route("/admin/niveaux", name="admin.niveaux")
     * @return Response
     */
    public function index(): Response{
        $niveaux = $this->repository->findAll();
        return $this->render(self::PAGENIVEAUX, [
            'niveaux' => $niveaux
        ]);
    }
}
