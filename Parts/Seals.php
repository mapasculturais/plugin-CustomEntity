<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Position;
use MapasCulturais\App;
use MapasCulturais\Themes\BaseV2\Theme;

class Seals extends Part
{
    use Traits\PartPosition;

    protected function getDefaultSinglePosition(): Position
    {
        return new Position(section: 'container', anchor: 'begin');
    }

    public function getSubParts(): array
    {
        return [
            SealRelations::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/seals');
        });

        $app->hook("template(search.{$entity_definition->slug}.search-filter-{$entity_definition->slug}):before", function () {
            /** @var Theme $this */

            $this->part('custom-entity/search/seals');
        });
    }
}

