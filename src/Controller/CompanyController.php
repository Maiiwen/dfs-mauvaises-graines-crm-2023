<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Model\ActionModel;
use App\Model\PaginatedDataModel;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Error;
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

    #[Route('/entreprise/modif/{id}', name: 'app_update_company')]
    public function update(
        ManagerRegistry $doctrine, int $id,
        Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        try {
            $company = $entityManager->getRepository(Company::class)->find($id);

            if (!$company) {
                throw $this->createNotFoundException(
                    'No company found for id '.$id
                );
            }

            $form = $this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
            $entityManager->flush();
            }
        } catch (Error) {
            $this->addFlash('error', "Aucune entreprise associée à l'id . $id");
            $this->redirectToRoute('app_company_index');
        }

        return $this->render('./company/company_update.html.twig', 
            [
                'form' => $form->createView(),
            ]
        );
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
    #[Route('/entreprise/modification/{id}', name: 'app_company_edit')]
    public function edit(
        ManagerRegistry $doctrine, int $id,
        Request $request): Response
    {
        $entityManager = $doctrine->getManager();

        try {
            $company = $entityManager->getRepository(Company::class)->find($id);

            if (!$company) {
                throw $this->createNotFoundException(
                    'No company found for id '.$id
                );
            }

            $form = $this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
            $entityManager->flush();
            }
        } catch (Error) {
            $this->addFlash('error', "Aucune entreprise associée à l'id . $id");
            $this->redirectToRoute('app_company_index');
        }

        return $this->render('./company/company_update.html.twig', 
            [
                'form' => $form->createView(),
            ]
        );
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
