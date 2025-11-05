<?php

namespace CustomEntity;

use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;

class EntityDefinition
{
    public readonly EntityGenerator $entityGenerator;
    public readonly ControllerGenerator $controllerGenerator;

    function __construct(
        public readonly string $slug,
        public readonly string $entity,
        public readonly string $table,

        /** @var Part[] */
        public readonly array $parts

    ) {
        $this->entityGenerator = new EntityGenerator($this);
        $this->controllerGenerator = new ControllerGenerator($this);
    }

    function init()
    {
        foreach ($this->getParts() as $part) {
            $part->init($this);
        }

        $app = App::i();
        foreach ($this->getFileGroups() as $group) {
            $app->registerFileGroup($this->slug, $group);
        }
    }

    function renderTemplate(string $filename, array $traits = []): string
    {
        $content = file_get_contents(__DIR__ . '/templates/' . $filename);

        $content = str_replace('_ENTITY_NAME_', $this->entity, $content);
        $content = str_replace('_ENTITY_TABLE_', $this->table, $content);
        $content = str_replace('_ENTITY_SLUG_', $this->slug, $content);

        foreach ($traits as $trait) {
            $trait = preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $trait);
            $content = str_replace('/** TRAITS **/', "/** TRAITS **/\n    use $trait;", $content);
        }

        return $content;
    }

    /**
     * @return Part[]
     */
    function getParts(?Part $parent = null): array
    {
        $parts = $parent ? $parent->getSubParts() : $this->parts;

        foreach ($parts as $part) {
            $parts = array_merge($parts, $part->getSubParts());
        }

        return $parts;
    }

    function getValidations(): array
    {
        $validations = [];

        foreach ($this->getParts() as $part) {
            foreach ($part->getEntityValidations() as $prop => $property_validations) {
                if (isset($validations[$prop])) {
                    $validations[$prop] = array_merge($validations[$prop], $property_validations);
                } else {
                    $validations[$prop] = $property_validations;
                }
            }
        }

        return $validations;
    }

    /**
     * @return FileGroup[]
     */
    function getFileGroups(): array
    {
        $file_groups = [];

        foreach ($this->getParts() as $part) {
            $file_groups = array_merge($file_groups, $part->getFileGroups());
        }

        return $file_groups;
    }

    public function getSingleSections(): array
    {
        $sections = [];
        // foreach($this->entityGenerator)
    }
}
