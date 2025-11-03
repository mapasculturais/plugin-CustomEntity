<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entity;
use MapasCulturais\Traits as CoreTraits;
use CustomEntity\Traits as Traits;

#[ORM\Table(name: "_ENTITY_TABLE_")]
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
#[ORM\HasLifecycleCallbacks]
class _ENTITY_NAME_ extends Entity
{
    /** TRAITS **/
    use CoreTraits\EntityPermissionCache;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(
        name: "id", 
        type: "integer", 
        nullable: false
    )]
    public $id;

    #[ORM\Column(
        name: "create_timestamp", 
        type: "datetime", 
        nullable: false,
        options: ['default' => "CURRENT_TIMESTAMP"],
    )]
    protected $createTimestamp;

    #[ORM\Column(
        name: "update_timestamp", 
        type: "datetime", 
        nullable: false,
        options: ['default' => "CURRENT_TIMESTAMP"],
    )]
    protected $updateTimestamp;

    #[ORM\Column(
        name: "status", 
        type: "smallint", 
        nullable: false,
        options: ['default' => self::STATUS_ENABLED],
    )]
    protected $status = self::STATUS_ENABLED;
}
