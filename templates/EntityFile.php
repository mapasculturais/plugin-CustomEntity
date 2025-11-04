<?php
namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entities\File;

#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
class _ENTITY_NAME_File extends File {
    #[ORM\ManyToOne(targetEntity: _ENTITY_NAME_::class)]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $owner;

    #[ORM\ManyToOne(targetEntity: _ENTITY_NAME_File::class)]
    #[ORM\JoinColumn(name: "parent_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $parent;
}
