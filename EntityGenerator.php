<?php

namespace CustomEntity;

use MapasCulturais\App;

class EntityGenerator
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const ENTITIES_PATH = __DIR__ . "/Entities/";

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
        $this->filename = self::ENTITIES_PATH . "{$this->entityName}.php";
        $this->className = __NAMESPACE__ . "\\Entities\\{$this->entityName}";
    }

    function log(string $message)
    {
        $app = App::i();
        $app->log->debug($message);
    }

    function generateHash()
    {
        return md5($this->renderEntityClass());
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

    function getPartTraits(Part $part): array
    {
        $traits = $part->entityTraits;

        foreach($part->subParts as $subpart) {
            $traits = array_merge($traits, $this->getPartTraits($subpart));
        }

        return array_unique($traits);
    }

    function getTraits(): array
    {
        $traits = [];
        foreach ($this->config->parts as $part) {
            /** @var Part $part */
            $traits = array_merge($traits, $this->getPartTraits($part));
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

    function renderEntityClass(): string
    {
        $class_content = $this->renderTemplate('Entity.php');

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

        file_put_contents($this->filename, $this->renderEntityClass());

        $pcache_class_content = $this->renderTemplate('EntityPermissionCache.php');
        file_put_contents(self::ENTITIES_PATH . "{$this->entityName}PermissionCache.php", $pcache_class_content);

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