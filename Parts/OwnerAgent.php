<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\Traits;

class OwnerAgent extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityOwnerAgent::class
        ];
    }
}
