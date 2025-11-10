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
    <form class="form" @submit="$event.preventDefault()">
        <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'before') ?>
        <div class="field search-filter__<?= $definition->slug ?>-status">
            <label> <?= $definition->text(i::__('Status da entidade')) ?> </label>
            <?php $this->applyTemplateHook("search-filter-{$definition->slug}-field", 'before') ?>
            <label class="verified">
                <input v-model="pseudoQuery['@verified']" type="checkbox">
                <?= $definition->text(i::__('Somente verificadas')) ?>
            </label>
            <?php $this->applyTemplateHook("search-filter-{$definition->slug}-field", 'after') ?>
        </div>
        <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'end') ?>
    </form>
    <?php $this->applyTemplateHook("search-filter-{$definition->slug}", 'after') ?>
    <a class="clear-filter" @click="clearFilters()"><?= $definition->text(i::__('Limpar todos os filtros')) ?></a>
</search-filter>

