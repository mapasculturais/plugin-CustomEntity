<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\PermissionCache;

/**
 * @property _ENTITY_NAME_ $owner
 */
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
class _ENTITY_NAME_PermissionCache extends PermissionCache
{

    #[ORM\ManyToOne(targetEntity: "CustomEntity\Entities\_ENTITY_NAME_")]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $owner;
}
