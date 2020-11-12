<?php

namespace App\Entity\ShardTwo;

use App\Entity\Link;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkEvenRepository")
 * @ORM\Table(name="link")
 */
class LinkEven extends Link
{
    const SHARD = 'shard_two';

    public function __construct()
    {
        $this->setShard(self::SHARD);
    }
}
