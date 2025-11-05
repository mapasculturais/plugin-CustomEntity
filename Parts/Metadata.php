<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class Metadata extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityMetadata::class
        ];
    }

    public function generateFiles(EntityGenerator $generator): void
    {
        $generator->renderFile('EntityMeta.php');
    }
}