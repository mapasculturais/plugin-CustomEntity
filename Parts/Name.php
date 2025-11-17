<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Parts\Traits as PartTraits;
use CustomEntity\Position;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\Entity as MapasEntity;
use MapasCulturais\i;
use MapasCulturais\Themes\BaseV2\Theme;

class Name extends Part
{
    use PartTraits\PartPosition;

    protected ?string $fieldType = null;
    protected array $options = [];
    protected array $optionsOrder = [];

    public function __construct(?array $config = null)
    {
        if (is_array($config)) {
            if (isset($config['fieldType'])) {
                $this->fieldType($config['fieldType']);
            }

            if (isset($config['options'])) {
                $this->options($config['options']);
            }
        }
    }

    public function fieldType(string $field_type): static
    {
        $this->fieldType = trim($field_type);

        return $this;
    }

    public function options(array $options): static
    {
        $formatted = [];
        $order = [];

        foreach ($options as $value => $label) {
            if (is_array($label) || is_object($label)) {
                continue;
            }

            $formatted_value = is_int($value) ? (string) $value : (string) $value;
            $formatted[$formatted_value] = (string) $label;
            $order[] = $formatted_value;
        }

        $this->options = $formatted;
        $this->optionsOrder = $order;

        if (!$this->fieldType) {
            $this->fieldType = 'select';
        }

        return $this;
    }

    protected function getDefaultEditPosition(): Position
    {
        return new Position(section: 'main', anchor: 'begin');
    }

    public function getEntityTraits(): array
    {
        return [
            Traits\EntityName::class
        ];
    }

    public function getEntityValidations(): array
    {
        return [
            'name' => [
                'required' => $this->requiredErrorMessage ?: i::__('O nome é obrigatório')
            ],
        ];
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;

        $this->editTemplateHook($entity_definition, function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/name');
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () {
            /** @var Theme $this */
            $this->part('custom-entity/edit/name');
        });

        $app->hook("entity(CustomEntity.Entities.{$entity_definition->entity}).propertiesMetadata", function (&$properties) use ($self) {
            if($self->fieldType && $self->fieldType == 'select') {
                if (!isset($properties['name'])) {
                    return;
                }

                $properties['name']['field_type'] = $self->fieldType;
                $properties['name']['options'] = $self->options;
                $properties['name']['optionsOrder'] = $self->optionsOrder;
            }
        });
    }
}
