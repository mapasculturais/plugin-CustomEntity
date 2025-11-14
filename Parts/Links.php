<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Links extends Part
{
    public function getSubParts(): array
    {
        return [
            MetaLists::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--more-info):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/links');
        });

        $app->hook("template({$entity_definition->slug}.single.tab-info--main):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/links');
        });
    }
}

