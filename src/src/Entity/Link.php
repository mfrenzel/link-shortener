<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


abstract class Link
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=4096)
     * @Assert\Url
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $url_hash;

    /**
     * @ORM\Column(type="string", length=7)
     */
    protected $shard;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        $this->url_hash = md5($url);
        return $this;
    }

    public function getUrlHash(): ?string
    {
        return $this->url_hash;
    }

    public function setShard(string $shard): self
    {
        $this->shard = $shard;
        return $this;
    }

    public function getShard(): string
    {
        return $this->shard;
    }
}
