<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class SealRelations extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntitySealRelation::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerSealRelation::class
        ];
    }

    public function generateFiles(EntityGenerator $generator): void
    {
        $generator->renderFile('EntitySealRelation.php');
    }
}