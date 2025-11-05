<?php

namespace CustomEntity\Parts;

use CustomEntity\OwnerPart;
use MapasCulturais\Traits;

class OwnerAgent extends OwnerPart
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityOwnerAgent::class
        ];
    }
}
