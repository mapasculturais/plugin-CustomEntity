<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Downloads extends Part
{
    use Traits\PartPosition;

    public function getSubParts(): array
    {
        return [
            Files::add()
        ];
    }

    public function getFileGroups(): array
    {
        return [
            new FileGroup('downloads', ['^.*$'], i::__('Arquivo para download'), false, null, true),
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/downloads');
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/downloads');
        });
    }
}

