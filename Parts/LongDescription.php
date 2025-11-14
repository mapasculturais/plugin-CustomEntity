<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\i;

class LongDescription extends Part
{
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

        $app->hook("template({$entity_definition->slug}.edit.tab-info--main):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/long-description');
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () use ($self) {
            /** @var Theme $this */
            if ($self->isRequired) {
                $this->part('custom-entity/edit/long-description');
            }
        });

        $app->hook("template({$entity_definition->slug}.single.tab-info--main):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/single/long-description');
        });
    }
}
