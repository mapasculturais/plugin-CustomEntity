<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Position;
use MapasCulturais\App;
use MapasCulturais\Themes\BaseV2\Theme;

class RelatedAgents extends Part
{
    use Traits\PartPosition;

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'aside', anchor: 'begin');
    }

    protected function getDefaultSinglePosition(): Position
    {
        return new Position(section: 'aside', anchor: 'end');
    }

    public function getSubParts(): array
    {
        return [
            AgentRelations::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/related-agents');
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/related-agents');
        });
    }
}
