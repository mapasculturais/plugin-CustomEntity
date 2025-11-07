<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class API extends Part
{
    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerAPI::class
        ];
    }
}
