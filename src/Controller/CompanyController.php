<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Model\ActionModel;
use App\Model\PaginatedDataModel;
use App\Repository\CompanyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Error;
use Exception;
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
                    'Code Postal' => 'zipCode',
                    'SIRET' => 'siret'

                ], $actions))->getData(),
                'page_title' => 'Liste des entreprises'
            ]
        );
    }


    /**
     * Method to create a new company, uses Symfony Forms and address autocomplete
     * @return Response
     */
    #[Route('/entreprises/ajout', name: 'app_company_add')]
    public function add(CompanyRepository $companyRepository, Request $request): Response
    {
        $form = $this->createForm(CompanyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company = new Company();
            $company->setName($form->get('name')->getData());
            $company->setStreet($form->get('street')->getData());
            $company->setCity($form->get('city')->getData());
            $company->setZipCode($form->get('zipCode')->getData());
            $company->setSiret($form->get('siret')->getData());

            $companyRepository->save($company, true);
            $this->addFlash('success', 'Entreprise ajoutée avec succès !');
            return $this->redirectToRoute('app_company_index', ['sort' => 'c.id', 'direction' => 'desc']);
        }

        return $this->render('./company/company_form.html.twig',
            [
                'form' => $form->createView(),
                'page_title' => 'Ajouter une entreprise'
            ]
        );
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
    public function edit(CompanyRepository $companyRepository, int $id, Request $request): Response
    {
        try {
            $company = $companyRepository->find($id);
            if (!$company) {
                throw new Exception(
                    'Aucune companie avec l\'ID ' . $id
                );
            }

            $form = $this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $companyRepository->save($company);
            }
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('./company/company_form.html.twig',
            [
                'form' => $form->createView(),
                'page_title' => 'Modifier l\'entreprise ' . $company->getName()
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
