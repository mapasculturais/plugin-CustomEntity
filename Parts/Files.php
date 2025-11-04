<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityGenerator;
use CustomEntity\Part;
use MapasCulturais\Traits;

class Files extends Part
{
    public function getEntityTraits(): array
    {
        return [
            Traits\EntityFiles::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            Traits\ControllerUploads::class
        ];
    }

    public function generateFiles(EntityGenerator $generator): void
    {
        $generator->renderFile('EntityFile.php');
    }
}

