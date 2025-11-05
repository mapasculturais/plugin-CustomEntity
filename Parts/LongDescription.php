<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class LongDescription extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityLongDescription::class
        ];
    }

    public function getEntityValidations(): array
    {
        if (!$this->isRequired) {
            return [];
        }

        return [
            'longDescription' => [
                'required' => $this->requiredErrorMessage ?: i::__('A descrição longa é obrigatória')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/long-description');
        });
    }
}
