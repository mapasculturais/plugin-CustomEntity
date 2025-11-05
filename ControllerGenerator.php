<?php

namespace CustomEntity;

use MapasCulturais\App;

class ControllerGenerator
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const CONTROLLERS_PATH = __DIR__ . "/Controllers/";

    public readonly string $slug;
    public readonly string $table;
    public readonly string $entityName;
    public readonly string $filename;
    public readonly string $className;

    function __construct(
        protected readonly EntityDefinition $entityDefinition
    ) {
        $this->slug = $this->entityDefinition->slug;
        $this->table = $this->entityDefinition->table;
        $this->entityName = $this->entityDefinition->entity;
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
        return md5($this->entityDefinition->renderTemplate('Controller.php', $this->getTraits()));
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
        foreach ($this->entityDefinition->getParts() as $part) {
            /** @var Part $part */
            $traits = array_merge($traits, $part->controllerTraits);
        }

        $traits = array_unique($traits);

        $traits = array_map(fn($trait) => str_replace('MapasCulturais\Traits', 'CoreTraits', $trait), $traits);

        return array_reverse($traits);
    }

    function renderFile(string $filename, array $traits = []): void
    {
        $class_content = $this->entityDefinition->renderTemplate($filename, $traits);

        file_put_contents(self::CONTROLLERS_PATH . "{$this->entityName}.php", $class_content);
    }

    function create(): string
    {
        if ($this->isUpdated()) {
            return $this->filename;
        }

        Plugin::log("Atualizando arquivo do controller $this->entityName");

        $this->renderFile('Controller.php', $this->getTraits());

        return $this->filename;
    }
}
