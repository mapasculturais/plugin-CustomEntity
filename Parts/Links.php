<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Themes\BaseV2\Theme;

class Links extends Part
{
    use Traits\PartPosition;

    public function getSubParts(): array
    {
        return [
            MetaLists::add()
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/links');
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/links');
        });
    }
}

