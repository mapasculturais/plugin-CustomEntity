<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use MapasCulturais\Traits;

class StatusArchive extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityArchive::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerArchive::class
        ];
    }
}
