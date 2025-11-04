<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class Name extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityName::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'name' => [
                'required' => i::__('O nome é obrigatório')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function (){
            /** @var Theme $this */
            $this->part('custom-entity/edit/name');
        });
    }
}
