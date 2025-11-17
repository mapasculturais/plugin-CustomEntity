<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Parts\Traits as PartTraits;
use CustomEntity\Position;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class LongDescription extends Part
{
    use PartTraits\PartPosition;

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'main', anchor: 'begin');
    }

    protected function getDefaultSinglePosition(): Position
    {
        return new Position(section: 'main', anchor: 'begin');
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityLongDescription::class
        ];
    }

    public function getEntityValidations(): array
    {
        if (!$this->isRequired) {
            return [];
        }

        return [
            'longDescription' => [
                'required' => $this->requiredErrorMessage ?: i::__('A descrição longa é obrigatória')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;

        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/long-description');
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () use ($self) {
            /** @var Theme $this */
            if ($self->isRequired) {
                $this->part('custom-entity/edit/long-description');
            }
        });

        $this->singleTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/long-description');
        });
    }
}
