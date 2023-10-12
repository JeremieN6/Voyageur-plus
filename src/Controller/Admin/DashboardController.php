<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Entity\Newsletter;
use App\Entity\Plan;
use App\Entity\Subscription;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Voyageur+');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Accueil');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Abonnements');
        yield MenuItem::linkToCrud('Plans', 'fas fa-paper-plane', Plan::class);
        yield MenuItem::linkToCrud('Souscriptions', 'fas fa-cart-plus', Subscription::class);
        yield MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Invoice::class);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-users', Users::class);
        yield MenuItem::linkToCrud('Newsletter', 'fa fa-newspaper-o', Newsletter::class);

    }
}
