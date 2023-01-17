<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
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
}
