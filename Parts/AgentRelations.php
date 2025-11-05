<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class AgentRelations extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityAgentRelation::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerAgentRelation::class
        ];
    }

    public function generateFiles(EntityGenerator $generator): void
    {
        $generator->renderFile('EntityAgentRelation.php');
    }
}