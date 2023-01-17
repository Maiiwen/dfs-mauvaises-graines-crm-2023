<?php

namespace App\Controller;

use App\Model\ActionModel;
use App\Model\PaginatedDataModel;
use App\Repository\CompanyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanyController extends AbstractController
{
    #[Route('/entreprises', name: 'app_company_index')]
    public function index(
        CompanyRepository  $companyRepository,
        PaginatorInterface $paginator,
        Request            $request): Response
    {
        $items = $paginator->paginate(
            $companyRepository->getPaginationQuery(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        $actions = [new ActionModel('app_company_show', '', 'bi-eye', 'primary'),
            new ActionModel('app_company_edit', '', 'bi-pen', 'warning'),
            new ActionModel('app_company_delete', '', 'bi-trash', 'danger')];

        return $this->render('company/index.html.twig', [
            'paginated_data' => (new PaginatedDataModel($items, [
                'Nom' => 'name',
                'Adresse' => 'street',
                'Ville' => 'city',
                'CP' => 'zipCode'

            ], $actions))->getData()
        ]);
    }


    /**
     * Method to create a new company, uses Symfony Forms and address autocomplete
     * @return Response
     */
    #[Route('/entreprises/ajout', name: 'app_company_add')]
    public function add(): Response
    {
        return $this->render('company/add.html.twig');
    }

    /**
     * Method to show company's details and Customer list of the company
     * @param int $id
     * @return Response
     */
    #[Route('/entreprises/{id}', name: 'app_company_show')]
    public function show(int $id): Response
    {
        return $this->render('company/show.html.twig');
    }

    /**
     * Method to edit a company, uses Symfony Forms and address autocomplete
     * @param int $id
     * @return Response
     */
    #[Route('/entreprises/modification/{id}', name: 'app_company_edit')]
    public function edit(int $id): Response
    {
        return $this->render('company/edit.html.twig');
    }

    /**
     * Method to delete a company with id on POST
     * @return Response
     */
    #[Route('/entreprises/suppression', name: 'app_company_delete')]
    public function delete(): Response
    {
        return $this->render('company/delete.html.twig');
    }

}
