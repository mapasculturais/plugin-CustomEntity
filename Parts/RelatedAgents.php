<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class RelatedAgents extends Part
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
        
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/related-agents');
        });
        
        $app->hook("template({$entity_definition->slug}.single.tab-info--container):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/related-agents');
        });
    }
}

