<?php


namespace App\Domain\Models\Event;


class EventType
{
    const LT = 1;
    const MOKUMOKU = 2;
    const HANDS_ON = 3;
    const HACKKATHON = 4;
    const SEMINAR = 5;
    const CONFERENCE = 6;
    const WORKSHOP = 7;
    const READING = 8;

    private $id;
    private $name;
    private $needle;

    //FIXME $idを渡さないように修正する
    public function __construct(int $id, string $name = null, string $needle = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->needle = $needle;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNeedle()
    {
        return $this->needle;
    }

}
