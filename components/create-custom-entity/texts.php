<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$definition = Plugin::$intance->getEntityDefinition();

return [
    'entidade criada' => $definition->text(i::__('Entidade criada!')),
    'criar entidade' => $definition->text(i::__('Criar entidade')),
    'criar rascunho' => $definition->text(i::__('Entidade criada em rascunho')),
];