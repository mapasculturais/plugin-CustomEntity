<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Header extends Part
{
    public function getSubParts(): array
    {
        return [
            Files::add()
        ];
    }

    public function getFileGroups(): array
    {
        return [
            new FileGroup('header', ['^image/(jpeg|png)$'], i::__('O arquivo enviado não é uma imagem válida.'), true),
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function () use ($entity_definition) {
            /** @var Theme $this */
            $this->part('custom-entity/edit/header');
        });
    }
}

