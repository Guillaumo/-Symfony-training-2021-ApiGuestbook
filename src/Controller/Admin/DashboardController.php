<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Conference;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        return $this->render('admin/my-dashboard.html.twig',[
            'title' => 'Bienvenu sur le tableau de bord des conférences',
            'description' => 'Vous allez pouvoir gérer les différents lieux de conférences ainsi que leurs commentaires',
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Guestbookapi');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Conférences', 'fas fa-map-marker-alt', Conference::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comments', Comment::class);
    }
}
