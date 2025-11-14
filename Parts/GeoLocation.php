<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Position;
use CustomEntity\Traits\PartPosition;
use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Traits as MapasTraits;
use MapasCulturais\Types\GeoPoint;

class GeoLocation extends Part
{
    use PartPosition;

    protected bool $showLatLongFields = false;

    public function showLatLongFields(bool $show = true): static
    {
        $this->showLatLongFields = $show;

        return $this;
    }

    protected function getDefaultEditPosition(): Position
    {
        return new Position('more-info', 'begin');
    }

    protected function getDefaultSinglePosition(): Position
    {
        return new Position('more-info', 'begin');
    }

    public function getEntityTraits(): array
    {
        return [
            MapasTraits\EntityGeoLocation::class
        ];
    }

    public function getEntityValidations(): array
    {
        if (!$this->isRequired) {
            return [];
        }

        $callback_validation = 'v::callback("' . self::class . '::validateLocation")';

        return [
            'location' => [
                'required' => i::__('A localização é obrigatória'),
                $callback_validation => i::__('Latitude e longitude não podem ser 0,0')
            ],
        ];
    }

    public static function validateLocation($location): bool
    {
        if (!$location) {
            return false;
        }

        if ($location instanceof GeoPoint) {
            $lat = $location->latitude;
            $lng = $location->longitude;
        } elseif (is_array($location)) {
            $lat = $location['lat'] ?? $location['latitude'] ?? ($location[1] ?? null);
            $lng = $location['lng'] ?? $location['longitude'] ?? ($location[0] ?? null);
        } elseif (is_object($location)) {
            $lat = $location->lat ?? $location->latitude ?? null;
            $lng = $location->lng ?? $location->longitude ?? null;
        } else {
            return false;
        }

        if ($lat == null || $lng == null) {
            return false;
        }

        return (float) $lat != 0.0 || (float) $lng != 0.0;
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;

        $this->editTemplateHook($entity_definition, function () use ($self) {
            /** @var Theme $this */
            $this->part('custom-entity/edit/geo-location', [
                'showLatLongFields' => $self->showLatLongFields,
            ]);
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () use ($self) {
            /** @var Theme $this */

            if ($self->isRequired) {
                $this->part('custom-entity/edit/geo-location', [
                    'showLatLongFields' => $self->showLatLongFields,
                ]);
            }
        });

        $app->hook("template(search.{$entity_definition->slug}.search-custom-entity):end", function () use ($app) {
            /** @var Theme $this */

            $this->part('custom-entity/search/geo-location');
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/geo-location');
        });
    }
}
