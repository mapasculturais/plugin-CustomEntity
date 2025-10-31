<?php

namespace CustomEntity;

use MapasCulturais\Plugin as MapasCulturaisPlugin;
use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends MapasCulturaisPlugin
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const DOCTRINE_TOOL = APPLICATION_PATH . "tools/doctrine";

    protected array $entities = [];

    public function __construct(array $config = [])
    {
        foreach ($config as $entity_slug => $entity_config) {
            $entity_config = (object) $entity_config;
            $config[$entity_slug] = $this->parseEntityConfig($entity_slug, $entity_config);
        }

        parent::__construct($config);

        $app = App::i();

        // para evitar loop infinito na atualização do scheme das entidades
        if ($_SERVER['SCRIPT_FILENAME'] == self::DOCTRINE_TOOL) {
            return;
        }

        foreach ($config as $entity_slug => $entity_config) {
            $this->registerEntity($entity_slug);
        }

        $this->updateScheme();

        $this->flagEntitiesAsUpdated();
    }

    protected function log(string $message)
    {
        $app = App::i();

        $app->log->debug($message);
    }

    public function _init()
    {
        // eval(\psy\sh());
    }

    public function register() {}

    protected function parseEntityConfig(string $entity_slug, object $entity_config)
    {
        $required_keys = [
            'entity' => i::__("$entity_slug: o nome da classe da entidade (chave 'entity') é obrigatória"),
            'table' => i::__("$entity_slug: o nome da tabela da entidade (chave 'table') é obrigatória"),
        ];

        foreach ($required_keys as $key => $error) {
            if (!isset($entity_config->$key)) {
                throw new \Exception($error);
            }
        }

        $entity_config->uses = array_reverse($entity_config->uses ?? []);

        return $entity_config;
    }

    protected function getEntityConfig(string $entity_slug): object
    {
        return $this->config[$entity_slug];
    }

    protected function generateEntityHash(string $entity_slug)
    {
        $entity_config = $this->getEntityConfig($entity_slug);

        $class_content = file_get_contents(__DIR__ . '/ENTITY_TEMPLATE.php');
        $string = $entity_slug . ':' . json_encode($entity_config->uses) . ':' . $class_content;

        return md5($string);
    }

    protected function getEntityUpdatedFlagFilename(string $entity_slug): string
    {
        return self::FLAGS_PATH . "$entity_slug.hash";
    }

    protected function flagEntitiesAsUpdated()
    {
        if (!is_dir(self::FLAGS_PATH)) {
            mkdir(self::FLAGS_PATH);
        };

        foreach (array_keys($this->entities) as $entity_slug) {
            $hash_filename = $this->getEntityUpdatedFlagFilename($entity_slug);

            $hash = $this->generateEntityHash($entity_slug);

            file_put_contents($hash_filename, $hash);
        }
    }

    function isEntityUpdated(string $entity_slug)
    {
        return false;
        $hash_filename = $this->getEntityUpdatedFlagFilename($entity_slug);

        if (!file_exists($hash_filename)) {
            return false;
        }

        $saved_hash = file_get_contents($hash_filename);
        $actual_hash = $this->generateEntityHash($entity_slug);

        return $saved_hash == $actual_hash;
    }

    protected function registerEntity(string $entity_slug)
    {
        $entity_config = $this->getEntityConfig($entity_slug);

        $hash = $this->generateEntityHash($entity_slug);

        $filename = $this->createEntity($entity_slug);

        $this->entities[$entity_slug] = (object) [
            'entity' => $entity_config->entity,
            'table' => $entity_config->table,
            'class' => __NAMESPACE__ . "\\Entities\\{$entity_config->entity}",
            'filename' => $filename,
            'hash' => $hash
        ];
    }

    protected function createEntity($entity_slug): string
    {
        $entity_config = $this->getEntityConfig($entity_slug);

        $entity_name = $entity_config->entity;
        $table_name = $entity_config->table;
        $traits = $entity_config->uses;

        $filename = __DIR__ . "/Entities/{$entity_name}.php";

        if ($this->isEntityUpdated($entity_slug)) {
            return $filename;
        }

        $class_content = file_get_contents(__DIR__ . '/ENTITY_TEMPLATE.php');

        $class_content = str_replace('ENTITY_TEMPLATE', $entity_name, $class_content);
        $class_content = str_replace('entity_table', $table_name, $class_content);

        foreach ($traits as $trait) {
            $trait = preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $trait);
            $class_content = str_replace('/** traits **/', "/** TRAITS **/\n    use $trait;", $class_content);
        }

        $this->log("Atualizando arquivo da entidade $entity_name");

        file_put_contents($filename, $class_content);

        return $filename;
    }

    public function updateScheme()
    {
        $app = App::i();

        foreach ($this->entities as $entity_slug => $config) {
            if ($this->isEntityUpdated($entity_slug)) {
                continue;
            }

            $sqls = [];
            $app->log->debug("atualizando scheme da custom entity $entity_slug ({$config->class} [{$config->table}])");

            exec(self::DOCTRINE_TOOL . " orm:schema-tool:update --dump-sql --complete --no-interaction | grep {$config->table}", $sqls);

            foreach ($sqls as $sql) {
                $this->log(">>>>>  $sql");
                $app->conn->executeQuery($sql);
            }
        }

        // eval(\psy\sh());
    }
}
