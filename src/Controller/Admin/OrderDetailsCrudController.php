<?php
namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Assuming `order` and `product` are relations in `OrderDetails`
            AssociationField::new('order') // Order entity relation
                ->setLabel('Order'),
            AssociationField::new('product') // Product entity relation
                ->setLabel('Product'),
            
            // If you have other fields, you can add them here:
            TextField::new('quantity') // For example, if there’s a 'quantity' field
                ->setLabel('Quantity'),
            TextField::new('price') // If there's a 'price' field
                ->setLabel('Price'),
        ];
    }
}
