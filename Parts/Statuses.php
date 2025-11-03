<?php

namespace CustomEntity\Parts;

use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\Entity;
use MapasCulturais\i;

class Statuses extends Part
{
    public function getSubParts(): array
    {
        return [
            StatusDraft::create(),
            StatusArchive::create(),
            SoftDelete::create(),
        ];
    }
}
