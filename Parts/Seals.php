<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Seals extends Part
{
    public function getSubParts(): array
    {
        return [
            SealRelations::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        
        $app->hook("template({$entity_definition->slug}.single.tab-info--container):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/seals');
        });

        $app->hook("template(search.{$entity_definition->slug}.search-filter-{$entity_definition->slug}):before", function () {
            /** @var Theme $this */

            $this->part('custom-entity/search/seals');
        });
    }
}

