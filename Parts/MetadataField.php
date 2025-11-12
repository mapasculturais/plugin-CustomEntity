<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use CustomEntity\Traits;
use MapasCulturais\App;
use MapasCulturais\Definitions\Metadata;
use MapasCulturais\i;

class MetadataField extends Part
{
    protected Metadata $definition;

    function __construct(
        public readonly array $name
    ) {
        $this->definition = new Metadata($name, []);
    }

    static function add($name = null): static
    {
        if (empty($name)) {
            throw new \Exception("O nome do metadado é obrigatório");
        }

        if (!is_string($name)) {
            throw new \Exception("O nome do metadado deve ser uma string");
        }

        return parent::add($name);
    }

    public function type(string $type): static
    {
        $this->definition->type = $type;
        return $this;
    }

    public function fieldType(string $type): static
    {
        $this->definition->field_type = $type;
        return $this;
    }

    public function label(string $label): static
    {
        $this->definition->label = $label;
        return $this;
    }

    public function defaultValue($default_value): static
    {
        $this->definition->default_value = $default_value;
        return $this;
    }

    public function required(string $error_message = ''): static
    {
        $this->definition->is_required = true;
        $this->definition->is_required_error_message = $error_message;
        return $this;
    }

    public function unique(string $error_message = ''): static
    {
        $this->definition->is_unique = true;
        $this->definition->is_unique_error_message = $error_message;
        return $this;
    }

    public function private(): static
    {
        $this->definition->private = true;
        return $this;
    }

    public function serializer(callable $serializer): static
    {
        $this->definition->serialize = $serializer;
        return $this;
    }

    public function unserializer(callable $unserializer): static
    {
        $this->definition->unserialize = $unserializer;
        return $this;
    }

    public function readonly(): static
    {
        $this->definition->readonly = true;
        return $this;
    }

    public function options(array $options, bool $numeric_key_value = false): static
    {
        $this->definition->options = $options;
        $this->definition->numericKeyValueOptions = $numeric_key_value;
        return $this;
    }

    public function getSubParts(): array
    {
        return [
            Metadata::add()
        ];
    }

    public function register(EntityDefinition $entity_definition)
    {
        $app = App::i();

        // $app->registerMetadata(new Metadata($))
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;
        $app->hook("template({$entity_definition->slug}.edit.tab-info--content):begin", function () use($self) {
            /** @var Theme $this */
            /** @var MetadataField $self  */
            $this->part('custom-entity/edit/metadata', ['definition' => $self->definition]);
        });

        /** @todo somente se obrigatório */
        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () {
            /** @var Theme $this */
            /** @var MetadataField $self  */
            $this->part('custom-entity/edit/metadata', ['definition' => $self->definition]);
        });
    }
}
