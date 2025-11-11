<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Traits as MapasTraits;

class GeoLocation extends Part
{
    public function getEntityTraits(): array
    {
        return [
            MapasTraits\EntityGeoLocation::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'location' => [
                'required' => i::__('A localização é obrigatória')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):end", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/geo-location');
        });

        $app->hook("template(search.{$entity_definition->slug}.search-custom-entity):end", function () use ($app) {
            /** @var Theme $this */

            $this->part('custom-entity/search/geo-location');
        });
    }
}
