<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class TermRelations extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityTaxonomies::class
        ];
    }

    public function generateFiles(EntityGenerator $generator): void
    {
        $generator->renderFile('EntityTermRelation.php');
    }
}
