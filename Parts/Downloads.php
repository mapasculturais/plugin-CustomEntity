<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Downloads extends Part
{
    public function getSubParts(): array
    {
        return [
            Files::create()
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
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/downloads');
        });
    }
}

