<?php

namespace CustomEntity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @property string $shortDescription
 */
trait EntityShortDescription
{
    #[ORM\Column(name: "short_description", type: "text", nullable: true)]
    protected $shortDescription;
}
