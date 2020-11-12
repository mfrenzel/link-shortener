<?php

namespace App\Entity\ShardOne;

use App\Entity\Link;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkOddRepository")
 * @ORM\Table(name="link")
 */
class LinkOdd extends Link
{
    const SHARD = 'shard_one';

    public function __construct()
    {
        $this->setShard(self::SHARD);
    }
}
