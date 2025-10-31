<?php

namespace CustomEntity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @property string $name
 */
trait EntityName
{
  #[ORM\Column(name: "name", type: "string", length: 255, nullable: false)]
  protected $name;
}
