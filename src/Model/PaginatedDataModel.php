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
    public array $actions;

    /**
     * @param PaginationInterface $items
     * @param array $props :
     * ex: [
     *  'Nom' => 'name',
     *  ...
     * ]
     * @param array $actions
     *
     */
    public function __construct(PaginationInterface $items, array $props, array $actions = [])
    {
        $this->items = $items;
        $this->props = $props;
        $this->actions = $actions;
    }

    public function getData() : array
    {
        return [
            'items' => $this->items,
            'props' => $this->props,
            'actions' => $this->actions,
        ];
    }



}