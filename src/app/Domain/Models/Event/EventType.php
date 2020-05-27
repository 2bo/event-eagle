<?php


namespace App\Domain\Models\Event;


class EventType
{
    private $id;
    private $name;
    private $needle;

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
