<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Delivery;
use App\Entity\OrderDetails;
use App\Entity\Service;
use App\Entity\Tax;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Village Green');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('utilsateurs', 'icon class', User::class);

        yield MenuItem::linkToCrud('Service', 'icon class', Service::class);

        yield MenuItem::linkToCrud('Taxe', 'icon class', Tax::class);

        yield MenuItem::linkToCrud('Produit', 'icon class', Product::class);

        yield MenuItem::linkToCrud('adresse', 'icon class', Address::class);

        yield MenuItem::linkToCrud('commande', 'icon class', Order::class);

        yield MenuItem::linkToCrud('commande-detail', 'icon class', OrderDetails::class);

        yield MenuItem::linkToCrud('livraison', 'icon class', Delivery::class);

    }
}
