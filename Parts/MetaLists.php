<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class MetaLists extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityMetaLists::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerMetaLists::class
        ];
    }
}
