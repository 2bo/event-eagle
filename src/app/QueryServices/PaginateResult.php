<?php


namespace App\QueryServices;


class PaginateResult
{
    private $total;
    private $perPage;
    private $currentPage;
    private $lastPage;
    private $data;

    /**
     * PaginateResult constructor.
     * @param $total
     * @param $currentPage
     * @param $lastPage
     * @param $data
     */
    public function __construct(int $total, int $perPage, int $currentPage, $data = [])
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->lastPage = (int)ceil($total / $perPage);
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    public function toArray($options = 0)
    {
        $array = [
            'total' => $this->total,
            'per_page' => $this->perPage,
            'current_page' => $this->currentPage,
            'lastPage' => $this->lastPage,
            'data' => $this->data
        ];
        return $array;
    }
}
