<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use CustomEntity\Traits;
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
            'name' => [
                'required' => i::__('A descrição longa é obrigatória')
            ],
        ];
    }
}
