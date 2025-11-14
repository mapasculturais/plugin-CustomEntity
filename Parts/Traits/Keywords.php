<?php

namespace CustomEntity\Parts\Traits;

use CustomEntity\EntityDefinition;
use MapasCulturais\App;

trait Keywords {
    public bool $useAsKeyword = false;
    public bool $unnaccentKeyword = false;
    public bool $lowerKeyword = false;

    /**
     * 
     * @param bool $unnaccent remove os acentos em ambos os lados da comparação
     * @param bool $lower transforma o texto em lowercase em ambos os lados da comparação
     * @return static
     */
    function useAsKeyword(bool $unnaccent = false, bool $lower = false): static
    {
        $this->useAsKeyword = true;
        $this->unnaccentKeyword = $unnaccent;
        $this->lowerKeyword = $lower;

        return $this;
    }

    function keywordJoin(EntityDefinition $entity_definition, callable $callable)
    {
        $app = App::i();
        $class = $entity_definition->entityClassName;
        $hook_class_path = $class::getHookClassPath();
        
        $app->hook("repo({$hook_class_path}).getIdsByKeywordDQL.join", $callable);
    }

    function keywordWhere(EntityDefinition $entity_definition, callable $callable)
    {
        $app = App::i();
        $class = $entity_definition->entityClassName;
        $hook_class_path = $class::getHookClassPath();
        
        $app->hook("repo({$hook_class_path}).getIdsByKeywordDQL.where", $callable);
    }
}