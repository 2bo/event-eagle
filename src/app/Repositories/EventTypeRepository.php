<?php


namespace App\Repositories;


use App\DataModels\EventType as EventTypeDataModel;
use App\Domain\Models\Event\EventTypeRepositoryInterface;
use App\Domain\Models\Event\EventType;

class EventTypeRepository implements EventTypeRepositoryInterface
{
    public function all(): array
    {
        $eventTypes = [];
        $dataModels = EventTypeDataModel::all();

        foreach ($dataModels as $dataModel) {
            $eventTypes[] = new EventType($dataModel->id, $dataModel->name, $dataModel->needle);
        }
        return $eventTypes;
    }
}
