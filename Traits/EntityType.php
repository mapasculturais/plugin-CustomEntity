<?php

namespace CustomEntity\Traits;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Traits\EntityTypes;

trait EntityType
{
    use EntityTypes;

    #[ORM\Column(name: "type", type: "smallint", nullable: false)]
    protected $_type;
}
