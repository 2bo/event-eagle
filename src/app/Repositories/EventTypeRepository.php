<?php


namespace App\Repositories;


use App\DataModels\EventType as EventTypeDataModel;
use App\Domain\Models\Event\EventType;
use App\Domain\Models\Event\EventTypeRepositoryInterface;

class EventTypeRepository implements EventTypeRepositoryInterface
{
    public function all(): array
    {
        $eventTypes = [];
        $dataModels = EventTypeDataModel::all();

        foreach ($dataModels as $dataModel) {
            $eventTypes[] = $dataModel->toDomainModel();
        }
        return $eventTypes;
    }

    public function findById(int $id): ?EventType
    {
        $eventType = EventTypeDataModel::find($id);
        if (is_null($eventType)) {
            return null;
        }
        return $eventType->toDomainModel();
    }
}
