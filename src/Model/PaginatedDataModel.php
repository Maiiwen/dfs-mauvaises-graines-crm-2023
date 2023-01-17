<?php
namespace App\Model;

use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Permet de passer des données paginées dans une vue via une variable "paginated_data"
 * /!\ TODO : Utiliser la méthode getData() pour obtenir le résultat
 */
class PaginatedDataModel
{
    public PaginationInterface $items;

    public array $props;

    /**
     * @param PaginationInterface $items
     * @param array $props :
     * ex: [
     *  'Nom' => 'name',
     *  ...
     * ]
     */
    public function __construct(PaginationInterface $items, array $props)
    {
        $this->items = $items;
        $this->props = $props;
    }

    public function getData() : array
    {
        return [
            'items' => $this->items,
            'props' => $this->props
        ];
    }



}