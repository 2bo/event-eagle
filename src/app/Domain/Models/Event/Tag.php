<?php


namespace App\Domain\Models\Event;


class Tag
{
    private $id;
    private $name;
    private $pattern;
    private $icon_url;

    /**
     * Tag constructor.
     * @param $id
     * @param $name
     * @param $pattern
     * @param $icon_url
     */
    public function __construct(int $id, string $name, ?string $pattern = null, ?string $icon_url = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pattern = $pattern;
        $this->icon_url = $icon_url;
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
     * @return string|null
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    /**
     * @return string|null
     */
    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

}
