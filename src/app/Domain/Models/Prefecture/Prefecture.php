<?php


namespace App\Domain\Models\Prefecture;


class Prefecture
{
    private $id;
    private $name;
    private $kanaName;
    private $englishName;

    public function __construct(PrefectureId $id, ?string $name = null, ?string $kanaName = null, ?string $englishName = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->kanaName = $kanaName;
        $this->englishName = $englishName;
    }

    /**
     * @return PrefectureId
     */
    public function getId(): PrefectureId
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getKanaName(): ?string
    {
        return $this->kanaName;
    }

    /**
     * @return string|null
     */
    public function getEnglishName(): ?string
    {
        return $this->englishName;
    }

}
