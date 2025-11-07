<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;
use Panel\Controller;

class Panel extends Part
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

        $app->hook('panel.nav', function (&$nav) use($definition) {
            $nav['main']['items'][] = [
                'route' => "panel/{$definition->slug}",
                'icon' => $definition->slug,
                'label' => $definition->text(i::__('Minhas Entidades')),
            ];
        });
        
        $app->hook("GET(panel.{$definition->slug})", function() {
            /** @var Controller $this */
            $this->requireAuthentication();

            $this->render('custom-entities');
        });
    }
}

