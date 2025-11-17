<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Parts\Traits as PartTraits;
use CustomEntity\Position;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class ShortDescription extends Part
{
    use PartTraits\PartPosition;

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'main', anchor: 'begin');
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityShortDescription::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'shortDescription' => [
                'required' => $this->requiredErrorMessage ?: i::__('A descrição curta é obrigatória')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/short-description');
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/short-description');
        });

    }
}
