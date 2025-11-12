<?php

namespace CustomEntity;

use MapasCulturais\App;

class EntityCssGenerator
{
    const FLAGS_PATH = PRIVATE_FILES_PATH . "CustomEntity/";
    const CSS_PATH = __DIR__ . "/assets/css/";

    public readonly string $filename;

    function __construct(
        public readonly EntityDefinition $entityDefinition
    ) {
        $this->filename = "custom-entity.{$this->entityDefinition->entity}.css";
    }

    function log(string $message)
    {
        $app = App::i();
        $app->log->debug($message);
    }

    function renderTemplate(): string
    {
        $color = $this->entityDefinition->color;

        return $this->entityDefinition->renderTemplate('style.css', replacements: [
            'color-500' => $color,
            'color-300' => Color::darken($color, 25),
            'color-700' => Color::lighten($color, 25),
        ]);
    }

    function generateHash(): string|false
    {
        return md5($this->renderTemplate());
    }

    function getUpdatedFlagFilename(): string
    {
        return self::FLAGS_PATH . "entity.{$this->entityDefinition->slug}.hash";
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
        $hash_filename = $this->getUpdatedFlagFilename();

        if (!file_exists($hash_filename)) {
            return false;
        }

        $saved_hash = file_get_contents($hash_filename);
        $actual_hash = $this->generateHash();

        return $saved_hash == $actual_hash;
    }

    function create(): string
    {
        if ($this->isUpdated()) {
            return self::CSS_PATH . $this->filename;
        }

        Plugin::log("Atualizando arquivo css da entidade {$this->entityDefinition->entity}");

        $content = $this->renderTemplate();;

        file_put_contents(self::CSS_PATH . $this->filename, $content);

        return self::CSS_PATH . $this->filename;
    }
}
