<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\OwnerPart;
use MapasCulturais\App;
use MapasCulturais\Traits;

class OwnerAgent extends OwnerPart
{
    public ?string $label = null;

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
        $app->hook("template({$entity_definition->slug}.edit.tab-info--aside):end", function () use ($self) {
            /** @var Theme $this */
            $this->part('custom-entity/edit/owner-agent', ['label' => $self->label]);
        });

        $app->hook("template({$entity_definition->slug}.single.tab-info--aside):end", function () use ($self) {
            /** @var Theme $this */
            $this->part('custom-entity/single/owner-agent', ['label' => $self->label]);
        });
    }
}
