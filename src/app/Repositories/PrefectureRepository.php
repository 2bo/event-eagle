<?php

namespace App\Repositories;

use App\Domain\Models\Prefecture\Prefecture;
use App\Domain\Models\Prefecture\PrefectureId;
use App\Domain\Models\Prefecture\PrefectureRepositoryInterface;
use App\DataModels\Prefecture as PrefectureDataModel;

class PrefectureRepository implements PrefectureRepositoryInterface
{
    public function save(Prefecture $prefecture)
    {
        $dataModel = new PrefectureDataModel();
        $dataModel->id = $prefecture->getId()->value();
        $dataModel->name = $prefecture->getName();
        $dataModel->kana_name = $prefecture->getKanaName();
        $dataModel->english_name = $prefecture->getEnglishName();
        $dataModel->save();
    }

    public function findById(PrefectureId $id): ?Prefecture
    {
        $dataModel = PrefectureDataModel::find($id->value());
        $prefecture = $this->convertDataModelToEntity($dataModel);
        return $prefecture;
    }

    public function findAll(): array
    {
        $prefectures = [];
        $dataModels = PrefectureDataModel::all();
        foreach ($dataModels as $dataModel) {
            $prefectures[] = $this->convertDataModelToEntity($dataModel);
        }
        return $prefectures;
    }

    public function findByName(?string $name): ?Prefecture
    {
        $dataModel = PrefectureDataModel::where('name', $name)->first();
        $prefecture = $this->convertDataModelToEntity($dataModel);
        return $prefecture;
    }

    private function convertDataModelToEntity(PrefectureDataModel $dataModel): ?Prefecture
    {
        if (!$dataModel) {
            return null;
        }
        $prefectureId = new PrefectureId($dataModel->id);
        return new Prefecture($prefectureId, $dataModel->name, $dataModel->kana_name, $dataModel->english_name);
    }

}
