<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\TermRelation;

/**
 * @property _ENTITY_NAME_ $owner
 */
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
class _ENTITY_NAME_TermRelation extends TermRelation
{

    #[ORM\ManyToOne(targetEntity: _ENTITY_NAME_::class)]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $owner;
}