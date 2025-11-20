<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Entity\Overlay;
use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Redirect to Projects page
        return $this->redirectToRoute('admin_project_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wolfly Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Content Management');
        yield MenuItem::linkToCrud('Projects', 'fa fa-folder', Project::class);
        yield MenuItem::linkToCrud('Overlays', 'fa fa-layer-group', Overlay::class);
        yield MenuItem::linkToCrud('Documents', 'fa fa-file-code', Document::class);
    }
}
