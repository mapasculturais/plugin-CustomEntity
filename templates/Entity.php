<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entity;
use MapasCulturais\Traits as CoreTraits;
use CustomEntity\Traits as Traits;

#[ORM\Table(name: "entity_table")]
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
#[ORM\HasLifecycleCallbacks]
class ENTITY_TEMPLATE extends Entity
{
    /** TRAITS **/
    use CoreTraits\EntityPermissionCache;

    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "entity_table_id_seq", allocationSize: 1, initialValue: 1)]
    public $id;
}
