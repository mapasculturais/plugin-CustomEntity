<?php

namespace CustomEntity;

use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\Definitions\Metadata;

class EntityDefinition
{
    public readonly string $entityClassName;
    public readonly string $controllerClassName;
    public readonly EntityGenerator $entityGenerator;
    public readonly ControllerGenerator $controllerGenerator;

    function __construct(
        public readonly string $slug,
        public readonly string $entity,
        public readonly string $table,
        public readonly OwnerPart $owner,

        /** @var Part[] */
        public readonly array $parts,

        /**
         * Nome do ícone definido no init do componente mc-icon ou um ícone qualquer do iconify.
         * Se o valor informado conter o caracter : (dois pontos) será considerado que é um ícone do iconify.
         */
        public readonly string $icon = 'app',
        public readonly array $texts = []

    ) {
        $this->entityGenerator = new EntityGenerator($this);
        $this->controllerGenerator = new ControllerGenerator($this);

        $this->entityClassName = $this->entityGenerator->className;
        $this->controllerClassName = $this->controllerGenerator->className;
    }

    function init()
    {
        foreach ($this->getParts() as $part) {
            $part->init($this);
        }

        $app = App::i();
        $self = $this;

        // define o ícone da entidade
        $app->hook('component(mc-icon).iconset', function(&$iconset) use($self) {
            if(strpos($self->icon, ':')){
                $iconset[$self->slug] = $self->icon;
            } else {
                $iconset[$self->slug] = $iconset[$self->icon] ?? '';
            }
        });
    }

    function register()
    {
        $app = App::i();
        foreach ($this->getFileGroups() as $group) {
            $app->registerFileGroup($this->slug, $group);
        }

        foreach ($this->getParts() as $part) {
            $part->register($this);

            foreach ($part->getEntityMetadata() as $key => $config) {
                $definition = new Metadata($key, $config);
                $app->registerMetadata($definition, $this->entityClassName);
            }
        }
    }

    function text(string $text): string
    {
        $replacements = [
            'suas entidades',
            'sua entidade',

            'das entidades',
            'da entidade',

            'minhas entidades',
            'minha entidade',

            'as entidades',
            'a entidade',

            'entidades',
            'entidade',
        ];

        foreach ($replacements as $from) {
            $to = $this->texts[$from] ?? false;
            if (!$to) {
                continue;
            }
            $text = str_replace(ucwords($from), ucwords($to), $text);
            $text = str_replace(ucfirst($from), ucfirst($to), $text);
            $text = str_replace(mb_strtolower($from), mb_strtolower($to), $text);
            $text = str_replace(mb_strtoupper($from), mb_strtoupper($to), $text);
        }

        return $text;
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

        return [$this->owner, ...$parts];
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
        return $sections;
    }
}
