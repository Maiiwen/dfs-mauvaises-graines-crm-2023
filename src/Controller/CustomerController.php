<?php

namespace App\Controller;

use App\Model\PaginatedDataModel;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    #[Route('/clients', name: 'app_customers')]
    public function index(
        CustomerRepository $customerRepository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $items = $paginator->paginate(
            $customerRepository->getPaginationQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );    

        return $this->render('customer/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Nom' => 'lastname',
                'Prenom' => 'firstname',
                'Email' => 'email',
                'Créé par' => 'createdBy'
            ]))->getData()
        ]);
    }
}
