<?php

namespace App\Controller;

use App\Model\PaginatedDataModel;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends AbstractController
{
    #[Route('/entreprises', name: 'app_company_index')]
    #[Route('/entreprises/detail/{id}', name: 'app_company_detail')]

    public function index(
        CompanyRepository $companyRepository,
        PaginatorInterface $paginator,
        Request $request): Response
    {
        $items = $paginator->paginate(
            $companyRepository->getPaginationQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );    

        return $this->render('company/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Nom' => 'name',
                'Adresse' => 'street',
                'Ville' => 'city',
                'CP' => 'zipCode'
            ]))->getData()
        ]);
    }

    /**
     * @Route("/entreprises/detail/{id}", name="app_company_detail")
     */
    public function detail(int $id, CompanyRepository $companyRepository, CustomerRepository $customerRepository)
    {
        $company = $companyRepository->find($id);
        $customers = $customerRepository->findBy(['company' => $company]);
        $totalCompanies = $companyRepository->count([]);
        return $this->render('company/details.html.twig', [
            'company' => $company,
            'totalCompanies' => $totalCompanies,
            'customers' => $customers
        ]);
    }


}
