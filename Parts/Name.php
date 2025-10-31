<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use CustomEntity\Traits;
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
}
