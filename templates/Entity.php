<?php

namespace CustomEntity\Entities;

use CustomEntity\Plugin;
use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\Entity;
use MapasCulturais\Traits as CoreTraits;
use CustomEntity\Traits as Traits;
use MapasCulturais\App;

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

    static function getValidations() {
        $app = App::i();
        $plugin = Plugin::$intance;

        $definition = $plugin->config['_ENTITY_SLUG_'];

        $validations = $definition->getValidations();

        $prefix = self::getHookPrefix();
        $app->applyHook("{$prefix}::validations", [&$validations]);

        return $validations;
    }

}
