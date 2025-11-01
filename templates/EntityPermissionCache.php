<?php
namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\PermissionCache;

#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
class ENTITY_TEMPLATEPermissionCache extends PermissionCache {

    #[ORM\ManyToOne(targetEntity: "CustomEntity\Entities\ENTITY_TEMPLATE")]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $owner;
}