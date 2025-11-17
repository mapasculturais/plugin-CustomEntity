<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Themes\BaseV2\Theme;

class VideoGallery extends Part
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
            $this->part('custom-entity/edit/video-gallery');
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/video-gallery');
        });
    }
}
