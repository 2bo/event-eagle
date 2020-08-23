<?php


namespace App\Domain\Models\Event;


class Tag
{
    private $id;
    private $name;
    private $pattern;
    private $url_name;
    private $icon_url;

    /**
     * Tag constructor.
     * @param $id
     * @param $name
     * @param $pattern
     * @param $icon_url
     */
    public function __construct(int $id, string $name, ?string $pattern = null, ?string $url_name = null, ?string $icon_url = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pattern = $pattern;
        $this->url_name = $url_name;
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
    public function getUrlName(): ?string
    {
        return $this->url_name;
    }

    /**
     * @return string|null
     */
    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function toArray()
    {
        return [

            'id' => $this->getId(),
            'name' => $this->getName(),
            'url_name' => $this->getUrlName(),
            'icon_url' => $this->getIconUrl(),
        ];
    }

}
