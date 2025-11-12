<?php

namespace CustomEntity\Parts;

use CustomEntity\EntityDefinition;
use CustomEntity\Part;
use MapasCulturais\App;
use MapasCulturais\Definitions\Metadata as MetadataDefinition;

class MetadataField extends Part
{
    protected ?MetadataDefinition $definition = null;
    protected array $config;

    function __construct(
        public readonly string $name
    ) {
        $this->config = [];
    }

    static function add($name = null): static
    {
        if (empty($name)) {
            throw new \Exception("O nome do metadado é obrigatório");
        }

        if (!is_string($name)) {
            throw new \Exception("O nome do metadado deve ser uma string");
        }

        $instance = parent::add($name);

        $instance->label($name);

        return $instance;
    }

    public function type(string $type): static
    {
        $this->config['type'] = $type;
        return $this;
    }

    public function fieldType(string $type): static
    {
        $this->config['field_type'] = $type;
        return $this;
    }

    public function label(string $label): static
    {
        $this->config['label'] = $label;
        return $this;
    }

    public function defaultValue($default_value): static
    {
        $this->config['default_value'] = $default_value;
        return $this;
    }

    public function required(string $error_message = ''): static
    {
        $validations = $this->config['validations'] ?? [];
        $validations['required'] = $error_message;
        $this->config['validations'] = $validations;
        return $this;
    }

    public function unique(string $error_message = ''): static
    {
        $validations = $this->config['validations'] ?? [];
        $validations['unique'] = $error_message;
        $this->config['validations'] = $validations;
        return $this;
    }

    public function private(): static
    {
        $this->config['private'] = true;
        return $this;
    }

    public function serializer(callable $serializer): static
    {
        $this->config['serialize'] = $serializer;
        return $this;
    }

    public function unserializer(callable $unserializer): static
    {
        $this->config['unserialize'] = $unserializer;
        return $this;
    }

    public function readonly(): static
    {
        $this->config['readonly'] = true;
        return $this;
    }

    public function options(array $options, bool $numeric_key_value = false): static
    {
        $this->config['options'] = $options;
        $this->config['numericKeyValueOptions'] = $numeric_key_value;
        return $this;
    }

    public function getSubParts(): array
    {
        return [
            Metadata::add()
        ];
    }

    protected function getDefinition(): MetadataDefinition
    {
        $this->definition = new MetadataDefinition($this->name, $this->config);

        return $this->definition;
    }

    public function register(EntityDefinition $entity_definition)
    {
        $app = App::i();

        $app->registerMetadata($this->getDefinition(), $entity_definition->entityClassName);
    }

    public function init(EntityDefinition $entity_definition)
    {
        $app = App::i();
        $self = $this;

        $app->hook("template({$entity_definition->slug}.edit.tab-info--more):begin", function () use($self) {
            /** @var Theme $this */

            $this->part('custom-entity/edit/metadata', ['definition' => $self->definition]);
        });

        $app->hook("template(<<*>>.<<*>>.create-{$entity_definition->slug}__fields):begin", function () use($self) {
            /** @var Theme $this */

            if($self->definition->is_required) {
                $this->part('custom-entity/edit/metadata', ['definition' => $self->definition]);
            }
        });
    }
}
