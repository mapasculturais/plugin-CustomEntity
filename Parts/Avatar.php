<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;
use MapasCulturais\Traits;

class Avatar extends Part
{
    public function getSubParts(): array
    {
        return [
            Files::add()
        ];
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityAvatar::class
        ];
    }

    public function getFileGroups(): array
    {
        return [
            new FileGroup('avatar', ['^image/(jpeg|png)$'], i::__('O arquivo enviado não é uma imagem válida.'), true),
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--main):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/avatar');
        });
    }
}
