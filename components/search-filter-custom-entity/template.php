<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->import('
    search-filter
');

$definition = Plugin::$intance->getEntityDefinition();

?>
<search-filter :position="position" :pseudo-query="pseudoQuery">
    <label class="form__label">
        <?= $definition->text(i::__('Filtros da entidade')) ?>
    </label>
    <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'begin') ?>
    <form class="form" @submit="$event.preventDefault()">
        <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'before') ?>
        
        <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'after') ?>
    </form>
    <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'end') ?>
    <a class="clear-filter" @click="clearFilters()"><?= $definition->text(i::__('Limpar todos os filtros')) ?></a>
</search-filter>

