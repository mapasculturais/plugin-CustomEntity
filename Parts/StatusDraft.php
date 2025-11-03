<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\Traits;

class StatusDraft extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityDraft::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerDraft::class
        ];
    }
}
