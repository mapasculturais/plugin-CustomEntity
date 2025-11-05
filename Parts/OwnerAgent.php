<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\OwnerPart;
use MapasCulturais\App;
use MapasCulturais\Traits;

class OwnerAgent extends OwnerPart
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityOwnerAgent::class
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content--right):end", function () use ($app) {
            /** @var Theme $this */
            $this->part('custom-entity/edit/owner-agent');
        });

        $app->hook("template({$entity_definition->slug}.single.tab-info--aside):end", function () use ($app) {
            /** @var Theme $this */
            $this->part('custom-entity/single/owner-agent');
        });
    }
}
