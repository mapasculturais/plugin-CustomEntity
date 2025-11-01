<?php

namespace CustomEntity;

use MapasCulturais\Plugin as MapasCulturaisPlugin;
use MapasCulturais\i;
use MapasCulturais\App;

class Plugin extends MapasCulturaisPlugin
{
    const DOCTRINE_TOOL = APPLICATION_PATH . "tools/doctrine";
    
    protected array $entities = [];

    public function __construct(array $config = [])
    {
        $entities_config = [];
        
        foreach ($config['entities']() as $entity_slug => $entity_config) {
            $entity_config = (object) $entity_config;
            $entities_config[$entity_slug] = $this->parseEntityConfig($entity_slug, $entity_config);
        }

        parent::__construct($entities_config);

        $app = App::i();

        // para evitar loop infinito na atualização do scheme das entidades
        if ($_SERVER['SCRIPT_FILENAME'] == self::DOCTRINE_TOOL) {
            return;
        }

        foreach ($entities_config as $entity_slug => $entity_config) {
            $this->registerEntity($entity_slug);
        }

        foreach ($this->entities as $entity_slug => $config) {
            $config->entityCreator->create();
        }

        $this->updateScheme();

        $this->flagEntitiesAsUpdated();
    }

    static function log(string $message)
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

    protected function flagEntitiesAsUpdated()
    {
        foreach ($this->entities as $entity_slug => $config) {
            $config->entityCreator->flagAsUpdated();
        }
    }

    protected function registerEntity(string $entity_slug)
    {
        $entity_config = $this->getEntityConfig($entity_slug);

        $this->entities[$entity_slug] = (object) [
            'entityCreator' => new EntityCreator($entity_slug, $entity_config),
            // 'controllerCreator' => new ControllerCreator($entity_slug, $entity_config),
        ];
    }

    protected function createEntity($entity_slug): string
    {
        
    }

    public function updateScheme()
    {
        foreach ($this->entities as $entity_slug => $config) {
            $config->entityCreator->updateScheme();
        }
    }
}
