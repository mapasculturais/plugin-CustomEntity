<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use CustomEntity\Traits;
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
            'name' => [
                'required' => i::__('A descrição curta é obrigatória')
            ],
        ];
    }
}
