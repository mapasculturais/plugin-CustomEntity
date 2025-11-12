<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\Traits;

class StatusPrivate extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityPrivate::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerPrivateEntity::class
        ];
    }
}
