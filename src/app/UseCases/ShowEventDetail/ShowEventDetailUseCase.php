<?php


namespace App\UseCases\ShowEventDetail;


use App\Repositories\EventRepository;

class ShowEventDetailUseCase implements ShowEventDetailUseCaseInterface
{

    private $repository;

    /**
     * ShowEventDetailUseCase constructor.
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ShowEventDetailInputData $input): ShowEventDetailOutputData
    {
        $id = $input->getId();
        $event = $this->repository->findById($id);
        return new ShowEventDetailOutputData($event);
    }
}
