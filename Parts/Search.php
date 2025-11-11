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

        $app->hook('template(<<*>>.<<*>>.mc-header-menu-projects):after', function() use ($definition, $app) {
            /** @var Theme $this */
            $url = $app->createUrl('search', $definition->slug);
            $icon = $definition->slug;
            $label = ucfirst($definition->texts['entidades']);
            
            $this->part('custom-entity/search/header-menu', [
                'url' => $url,
                'icon' => $icon,
                'label' => $label,
                'slug' => $definition->slug,
            ]);
        });
    }
}

