<?php

namespace CustomEntity;

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

    
    function renderTemplate(string $filename, array $traits = []): string
    {
        $content = file_get_contents(__DIR__ . '/templates/' . $filename);

        $content = str_replace('_ENTITY_NAME_', $this->entity, $content);
        $content = str_replace('_ENTITY_TABLE_', $this->table, $content);

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

    public function getSingleSections(): array
    {
        $sections = [];
        // foreach($this->entityGenerator)
    }
}
