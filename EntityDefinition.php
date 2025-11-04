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
}
