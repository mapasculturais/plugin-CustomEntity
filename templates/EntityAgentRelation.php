<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\AgentRelation;

/**
 * @property _ENTITY_NAME_ $owner
 */
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
class _ENTITY_NAME_AgentRelation extends AgentRelation
{

    #[ORM\ManyToOne(targetEntity: _ENTITY_NAME_::class)]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $owner;
}
