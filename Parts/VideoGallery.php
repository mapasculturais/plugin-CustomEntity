<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;
use MapasCulturais\Traits;

class VideoGallery extends Part
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
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/video-gallery');
        });
    }
}
