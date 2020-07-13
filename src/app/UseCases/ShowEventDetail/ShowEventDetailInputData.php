<?php


namespace App\UseCases\ShowEventDetail;


class ShowEventDetailInputData
{
    private $id;

    /**
     * ShowEventDetailInputData constructor.
     * @param $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

}
