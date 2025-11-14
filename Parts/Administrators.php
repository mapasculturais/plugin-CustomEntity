<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Administrators extends Part
{
    public function getSubParts(): array
    {
        return [
            AgentRelations::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $app->hook("template({$entity_definition->slug}.edit.tab-info--aside):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/administrators');
        });

        $app->hook("template({$entity_definition->slug}.single.tab-info--aside):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/administrators');
        });
    }
}

