<?php

namespace CustomEntity;

use MapasCulturais\Traits\MagicGetter;

/**
 * @property-read Part[] $subParts

 * @property-read array $entityTraits
 * @property-read array $entityValidations
 * @property-read array $entityMetadata

 * @property-read array $controllerTraits
  
 * @property-read array $FileGroups

 */
abstract class Part
{
    use MagicGetter;

    protected bool $isRequired = false;

    protected string $requiredErrorMessage = '';

    static function add($config = null): static
    {
        return new static($config);
    }

    function required(string $error_message = ''): static
    {
        $this->isRequired = true;
        $this->requiredErrorMessage = $error_message;

        return $this;
    }

    /**
     * @return Part[]
     */
    public function getSubParts(): array
    {
        return [];
    }

    public function getEntityTraits(): array
    {
        return [];
    }

    public function getEntityValidations(): array
    {
        return [];
    }

    public function getEntityMetadata(): array
    {
        return [];
    }

    public function getControllerTraits(): array
    {
        return [];
    }

    public function getFileGroups(): array
    {
        return [];
    }

    public function generateFiles(EntityGenerator $generator): void {}

    public function register(EntityDefinition $entity_definition) {}
    public function init(EntityDefinition $entity_definition) {}
}
