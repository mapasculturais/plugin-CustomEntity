<?php

namespace CustomEntity\Entities;

use Doctrine\ORM\Mapping as ORM;

use MapasCulturais\App;

/**
 * @property _ENTITY_NAME_ $owner
 */
#[ORM\Table(name: "_ENTITY_TABLE__meta", indexes: [
    new ORM\Index(name: "_ENTITY_TABLE__meta_owner_idx", columns: ["object_id"]),
    new ORM\Index(name: "_ENTITY_TABLE__meta_owner_key_idx", columns: ["object_id", "key"]),
    new ORM\Index(name: "_ENTITY_TABLE__meta_key_idx", columns: ["key"]),
    new ORM\Index(name: "_ENTITY_TABLE__meta_value_idx", columns: ["value"], flags: ["fulltext"])
])]
#[ORM\Entity(repositoryClass: "MapasCulturais\Repository")]
#[ORM\HasLifecycleCallbacks]
class _ENTITY_NAME_Meta extends \MapasCulturais\EntityMetadata
{

    /**
     * @var integer
     */
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    public $id;

    /**
     * @var _ENTITY_NAME_
     */
    #[ORM\ManyToOne(targetEntity: _ENTITY_NAME_::class)]
    #[ORM\JoinColumn(name: "object_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    protected $owner;

    public function canUser($action, $userOrAgent = null)
    {
        return $this->owner->canUser($action, $userOrAgent);
    }

    #[ORM\PrePersist]
    public function _prePersist($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').insert:before', [$args]);
    }
    #[ORM\PostPersist]
    public function _postPersist($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').insert:after', [$args]);
    }

    #[ORM\PreRemove]
    public function _preRemove($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').remove:before', [$args]);
    }
    #[ORM\PostRemove]
    public function _postRemove($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').remove:after', [$args]);
    }

    #[ORM\PreUpdate]
    public function _preUpdate($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').update:before', [$args]);
    }
    #[ORM\PostUpdate]
    public function _postUpdate($args = null)
    {
        App::i()->applyHookBoundTo($this, 'entity(_ENTITY_NAME_).meta(' . $this->key . ').update:after', [$args]);
    }

    //============================================================= //
    // The following lines ara used by MapasCulturais hook system.
    // Please do not change them.
    // ============================================================ //

    #[ORM\PrePersist]
    public function prePersist($args = null)
    {
        parent::prePersist($args);
    }
    #[ORM\PostPersist]
    public function postPersist($args = null)
    {
        parent::postPersist($args);
    }

    #[ORM\PreRemove]
    public function preRemove($args = null)
    {
        parent::preRemove($args);
    }
    #[ORM\PostRemove]
    public function postRemove($args = null)
    {
        parent::postRemove($args);
    }

    #[ORM\PreUpdate]
    public function preUpdate($args = null)
    {
        parent::preUpdate($args);
    }
    #[ORM\PostUpdate]
    public function postUpdate($args = null)
    {
        parent::postUpdate($args);
    }
}
