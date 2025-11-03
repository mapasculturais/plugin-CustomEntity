<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\Traits;

class SoftDelete extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntitySoftDelete::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerSoftDelete::class
        ];
    }
}
