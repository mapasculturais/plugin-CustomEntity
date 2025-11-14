<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\Definitions\EntityType;
use MapasCulturais\i;
use MapasCulturais\Traits as MapasTraits;
use MapasCulturais\Themes\BaseV2\Theme;

class Type extends Part
{
    function __construct(
        public readonly array $types
    ) {}

    static function add($types = null): static
    {
        if (empty($types)) {
            throw new \Exception("A lista de tipos é obrigatória");
        }

        if (!is_array($types)) {
            throw new \Exception("A lista de tipos deve ser um array no formato [id => label]");
        }

        return parent::add($types);
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityType::class
        ];
    }

    public function getControllerTraits(): array
    {
        return [
            MapasTraits\ControllerTypes::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'type' => [
                'required' => $this->requiredErrorMessage ?: i::__('O tipo é obrigatório')
            ],
        ];
    }

    public function register(EntityDefinition $entity_definition)
    {
        $app = App::i();

        foreach ($this->types as $id => $label) {
            $app->registerEntityType(new EntityType($entity_definition->entityClassName, $id, $label));
        }
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/type');
        }, -10);

        $types = $this->types;

        $app->hook("template(search.{$entity_definition->slug}.search-filter-{$entity_definition->slug}):after", function () use ($types) {
            /** @var Theme $this */

            $this->part('custom-entity/search/type', ['types' => $types]);
        });
    }
}
