<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\OwnerPart;
use CustomEntity\Parts\Traits as PartTraits;
use CustomEntity\Position;
use MapasCulturais\App;
use MapasCulturais\Traits;

class OwnerAgent extends OwnerPart
{
    use PartTraits\PartPosition;

    public ?string $label = null;

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'aside', anchor: 'end');
    }

    protected function getDefaultSinglePosition(): Position
    {
        return new Position(section: 'aside', anchor: 'end');
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityOwnerAgent::class
        ];
    }

    public function label(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;
        $this->editTemplateHook($entity_definition, function () use ($self) {
            /** @var Theme $this */
            $this->part('custom-entity/edit/owner-agent', ['label' => $self->label]);
        });

        $this->singleTemplateHook($entity_definition, function () use ($self) {
            /** @var Theme $this */
            $this->part('custom-entity/single/owner-agent', ['label' => $self->label]);
        });
    }
}
