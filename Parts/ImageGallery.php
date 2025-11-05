<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class ImageGallery extends Part
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
            new FileGroup('gallery', ['^image/(jpeg|png|gif|webp)$'], i::__('O arquivo enviado não é uma imagem válida.'), false),
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/image-gallery');
        });
    }
}
