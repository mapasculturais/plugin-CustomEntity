<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class ShortDescription extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityShortDescription::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'shortDescription' => [
                'required' => i::__('A descrição curta é obrigatória')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function (){
            /** @var Theme $this */
            $this->part('custom-entity/edit/short-description');
        });
    }
}
