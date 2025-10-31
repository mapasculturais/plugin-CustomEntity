<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\i;
use MapasCulturais\Traits as MapasTraits;

class GeoLocation extends Part
{
    public function getEntityTraits(): array
    {
        return [
            MapasTraits\EntityGeoLocation::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'location' => [
                'required' => i::__('A localização é obrigatória')
            ],
        ];
    }
}
