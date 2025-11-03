<?php

namespace CustomEntity\Traits;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\Agent;

/**
 * @property Agent $name
 */
trait EntityOwnerAgent
{
    #[ORM\ManyToOne(targetEntity: "MapasCulturais\Entities\Agent", fetch: "LAZY")]
    #[ORM\JoinColumn(name: "agent_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected Agent $owner;
}
