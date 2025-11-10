<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;
use Panel\Controller;

class Search extends Part
{
    public function getSubParts(): array
    {
        return [
            API::add(),
        ];
    }

    public function init(EntityDefinition $definition)
    {
        $app = App::i();

        $app->hook("GET(search.{$definition->slug})", function() {
            /** @var Controller $this */

            $this->render('custom-entity');
        });
    }
}

