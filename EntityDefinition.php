<?php

namespace CustomEntity;

use MapasCulturais\App;
use MapasCulturais\Definitions\FileGroup;
use MapasCulturais\Definitions\Metadata;
use MapasCulturais\i;

class EntityDefinition
{
    public readonly string $entityClassName;
    public readonly string $controllerClassName;
    public readonly EntityGenerator $entityGenerator;
    public readonly ControllerGenerator $controllerGenerator;
    public readonly EntityCssGenerator $entityCssGenerator;
    public readonly array $editSections;
    public readonly array $singleSections;

    function __construct(
        public readonly string $slug,
        public readonly OwnerPart $owner,

        /**
         * Nome do ícone definido no init do componente mc-icon ou um ícone qualquer do iconify.
         * Se o valor informado conter o caracter : (dois pontos) será considerado que é um ícone do iconify.
        */
        public readonly string $icon = 'app',
        public readonly string $color = '#19d758',
        public readonly array $texts = [],

        /** @var Part[] */
        public readonly array $parts = [],
        ?array $editSections = null,
        ?array $singleSections = null,

        public ?string $entity = null,
        public ?string $table = null,
    ) {
        $this->entity = $this->entity ?: ucfirst($this->slug);
        $this->table = $this->table ?: $this->slug;

        $this->entityGenerator = new EntityGenerator($this);
        $this->controllerGenerator = new ControllerGenerator($this);
        $this->entityCssGenerator = new EntityCssGenerator($this);

        $this->entityClassName = $this->entityGenerator->className;
        $this->controllerClassName = $this->controllerGenerator->className;

        $this->editSections = $editSections ?? [
            'more-info' => i::__('Mais informações'),
        ];
        $this->singleSections = $singleSections ?? [
            'more-info' => i::__('Mais informações'),
        ];
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

        $app->hook('GET(<<*>>):before', function () use($self, $app) {
            /** @var EntityDefinition $this */

            $app->view->enqueueStyle('app-v2', 'custom-entity--' . $self->slug, 'css/' . $self->entityCssGenerator->filename);
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

    function renderTemplate(string $filename, array $traits = [], array $replacements = []): string
    {
        $content = file_get_contents(__DIR__ . '/templates/' . $filename);

        $content = str_replace('_ENTITY_NAME_', $this->entity, $content);
        $content = str_replace('_ENTITY_TABLE_', $this->table, $content);
        $content = str_replace('_ENTITY_SLUG_', $this->slug, $content);

        foreach ($traits as $trait) {
            $trait = preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $trait);
            $content = str_replace('/** TRAITS **/', "/** TRAITS **/\n    use $trait;", $content);
        }

        foreach ($replacements as $from => $to) {
            $content = str_replace($from, $to, $content);
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
