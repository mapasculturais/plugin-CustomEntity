<?php

namespace CustomEntity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @property string $shortDescription
 */
trait EntityLongDescription
{
    #[ORM\Column(name: "long_description", type: "text", nullable: true)]
    protected $longDescription;
}
