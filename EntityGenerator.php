<?php

namespace CustomEntity;

use MapasCulturais\App;

class EntityGenerator
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const ENTITIES_PATH = __DIR__ . "/Entities/";

    public readonly string $slug;
    public readonly string $table;
    public readonly string $entityName;
    public readonly string $filename;
    public readonly string $className;


    function __construct(
        public readonly EntityDefinition $entityDefinition
    ) {
        $this->slug = $entityDefinition->slug;
        $this->table = $this->entityDefinition->table;
        $this->entityName = $this->entityDefinition->entity;
        $this->filename = self::ENTITIES_PATH . "{$this->entityName}.php";
        $this->className = __NAMESPACE__ . "\\Entities\\{$this->entityName}";
    }

    function log(string $message)
    {
        $app = App::i();
        $app->log->debug($message);
    }

    function generateHash(): string|false
    {
        return md5($this->entityDefinition->renderTemplate('Entity.php', $this->getTraits()));
    }

    function getUpdatedFlagFilename(): string
    {
        return self::FLAGS_PATH . "entity.{$this->slug}.hash";
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
            $traits = array_merge($traits, $part->entityTraits);
        }

        $traits = array_unique($traits);

        $traits = array_map(fn($trait) => str_replace('MapasCulturais\Traits', 'CoreTraits', $trait), $traits);

        return array_reverse($traits);
    }

    function renderFile(string $filename, array $traits = []): void
    {
        $class_content = $this->entityDefinition->renderTemplate($filename, $traits);

        $destination_filename = preg_replace('#^Entity#', $this->entityName, $filename);

        file_put_contents(self::ENTITIES_PATH . "{$destination_filename}", $class_content);
    }

    function create(): string
    {
        if ($this->isUpdated()) {
            return $this->filename;
        }

        Plugin::log("Atualizando arquivo da entidade $this->entityName");

        $this->renderFile('Entity.php', $this->getTraits());

        $this->renderFile('EntityPermissionCache.php');

        foreach ($this->entityDefinition->getParts() as $part) {
            $part->generateFiles($this);
        }

        $app = App::i();

        $app->em->getConfiguration()->getMetadataCache()->clear();

        return $this->filename;
    }

    public function updateScheme()
    {
        $app = App::i();

        if ($this->isUpdated()) {
            return;
        }

        $sqls = [];
        Plugin::log("atualizando scheme da custom entity $this->slug ({$this->className} [{$this->table}])");

        exec(Plugin::DOCTRINE_TOOL . " orm:schema-tool:update --dump-sql --complete --no-interaction | grep {$this->table}", $sqls);

        foreach ($sqls as $sql) {
            Plugin::log(">>>>>  $sql");
            $app->conn->executeQuery($sql);
        }
    }
}
