<?php

namespace CustomEntity;

use MapasCulturais\Plugin as MapasCulturaisPlugin;
use MapasCulturais\i;
use MapasCulturais\App;
use MapasCulturais\Traits\Singleton;

/**
 * @property-read EntityDefinition[] $config
 * @package CustomEntity
 */
class Plugin extends MapasCulturaisPlugin
{
    const DOCTRINE_TOOL = APPLICATION_PATH . "tools/doctrine";
    public static self $intance;

    protected array $entities = [];

    public function __construct(array $config = [])
    {
        self::$intance = $this;

        $entities_config = [];
        foreach ($config['entities']() as $entity_definition) {
            /** @var EntityDefinition $entity_definition */
            $entities_config[$entity_definition->slug] = $entity_definition;
        }

        parent::__construct($entities_config);
    }

    static function log(string $message)
    {
        $app = App::i();

        $app->log->debug($message);
    }

    public function _init()
    {
        $app = App::i();

        // para evitar loop infinito na atualização do scheme das entidades
        if ($_SERVER['SCRIPT_FILENAME'] == self::DOCTRINE_TOOL) {
            return;
        }

        foreach ($this->config as $entity_slug => $definition) {
            $definition->entityGenerator->create();
            $definition->controllerGenerator->create();
        }

        $this->updateScheme();

        $this->flagEntitiesAsUpdated();

        foreach ($this->config as $entity_slug => $definition) {
            $entity_generator = $definition->entityGenerator;

            $this->addEntityDescriptionToJs($entity_slug, $entity_generator);
        }

        foreach ($this->config as $definition) {
            $definition->init();
        }
    }

    public function register() {
        $app = App::i();
        foreach ($this->config as $entity_slug => $definition) {

            $controller_generator = $definition->controllerGenerator;

            // register controller
            $app->registerController($entity_slug, $controller_generator->className, view_dir: 'custom-entity');

            $definition->register();
        }
    }

    public function getEntityConfig(?string $entity_slug = null): object
    {
        return $this->config[$entity_slug];
    }

    protected function flagEntitiesAsUpdated(): void
    {
        foreach ($this->config as $config) {
            $config->entityGenerator->flagAsUpdated();
            $config->controllerGenerator->flagAsUpdated();
        }
    }

    public function updateScheme(): void
    {
        foreach ($this->config as $config) {
            $config->entityGenerator->updateScheme();
        }
    }

    public function addEntityDescriptionToJs(string $entity_slug, EntityGenerator $generator): void
    {
        $app = App::i();

        $app->hook('mapas.printJsObject:before', function () use ($entity_slug, $generator) {
            /** @var \MapasCulturais\Themes\BaseV2\Theme */
            $entity_class = $generator->className;

            $this->jsObject['EntitiesDescription'][$entity_slug] = $entity_class::getPropertiesMetadata();
        });
    }

    static function getEntityDefinition(?string $entity_slug = null): ?EntityDefinition
    {
        if (is_null($entity_slug)) {
            $app = App::i();
            $entity_slug = $app->view->controller->id;
        }

        return self::$intance->config[$entity_slug] ?? null;
    }
}
