<?php

namespace CustomEntity;

use MapasCulturais\App;

class ControllerGenerator
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const CONTROLLERS_PATH = __DIR__ . "/Controllers/";

    public readonly string $table;
    public readonly string $entityName;
    public readonly string $filename;
    public readonly string $className;

    function __construct(
        protected readonly string $slug,
        protected readonly object $config
    ) {
        $this->table = $this->config->table;
        $this->entityName = $this->config->entity;
        $this->filename = self::CONTROLLERS_PATH . "{$this->entityName}.php";
        $this->className = __NAMESPACE__ . "\\Controllers\\{$this->entityName}";
    }

    function log(string $message)
    {
        $app = App::i();
        $app->log->debug($message);
    }

    function generateHash()
    {
        return md5($this->renderControllerClass());
    }

    function getUpdatedFlagFilename(): string
    {
        return self::FLAGS_PATH . "controller.{$this->slug}.hash";
    }

    function flagAsUpdated()
    {
        if (!is_dir(self::FLAGS_PATH)) {
            mkdir(self::FLAGS_PATH);
        };

        $hash_filename = $this->getUpdatedFlagFilename();

        $hash = $this->generateHash();

        file_put_contents($hash_filename, $hash);
    }

    function isUpdated()
    {
        return false;
        $hash_filename = $this->getUpdatedFlagFilename();

        if (!file_exists($hash_filename)) {
            return false;
        }

        $saved_hash = file_get_contents($hash_filename);
        $actual_hash = $this->generateHash();

        return $saved_hash == $actual_hash;
    }

    function getTraits(): array
    {
        $traits = [];
        foreach ($this->config->parts as $part) {
            /** @var Part $part */
            $traits = array_merge($traits, $part->controllerTraits);
        }
        $traits = array_unique($traits);

        $traits = array_map(fn($trait) => str_replace('MapasCulturais\Traits', 'CoreTraits', $trait), $traits);

        return array_reverse($traits);
    }

    function renderTemplate(string $filename): string
    {
        $content = file_get_contents(__DIR__ . '/templates/' . $filename);

        $content = str_replace('_ENTITY_NAME_', $this->entityName, $content);
        $content = str_replace('_ENTITY_TABLE_', $this->table, $content);

        return $content;
    }

    function renderControllerClass(): string
    {
        $class_content = $this->renderTemplate('Controller.php');

        foreach ($this->getTraits() as $trait) {
            $trait = preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $trait);
            $class_content = str_replace('/** TRAITS **/', "/** TRAITS **/\n    use $trait;", $class_content);
        }

        return $class_content;
    }

    function create(): string
    {
        if ($this->isUpdated()) {
            return $this->filename;
        }
        
        Plugin::log("Atualizando arquivo da entidade $this->entityName");

        file_put_contents($this->filename, $this->renderControllerClass());

        return $this->filename;
    }
}