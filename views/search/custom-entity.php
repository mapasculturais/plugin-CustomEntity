<?php 
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->import('
    create-custom-entity
    mc-tab
    mc-tabs
    search
    search-filter-custom-entity
    search-list
    search-map
');

$definition = Plugin::$intance->getEntityDefinition();

$this->breadcrumb = [
    ['label'=> i::__('Inicio'), 'url' => $app->createUrl('site', 'index')],
    ['label'=> $definition->text(i::__('Entidades')), 'url' => $app->createUrl('search', $definition->slug)],
];
?>
<search page-title="<?= htmlspecialchars($definition->text(i::__('Entidades'))) ?>" entity-type="<?= $definition->slug ?>" :initial-pseudo-query="{}">
    <template #create-button>
        <create-custom-entity v-if="global.auth.isLoggedIn" :editable="true" #default="{modal}">
            <button @click="modal.open()" class="button button--primary button--icon">
                <mc-icon name="add"></mc-icon>
                <span><?= $definition->text(i::__('Criar Entidade')) ?></span>
            </button>
        </create-custom-entity>
    </template>
    <template #default="{pseudoQuery, changeTab}">
        <?php $this->applyTemplateHook('search-custom-entity', 'before') ?>
        <mc-tabs @changed="changeTab($event)" class="search__tabs" sync-hash>
            <template  #before-tablist>
                <label class="search__tabs--before">
                    <?= $definition->text(i::__('Visualizar como:')) ?>
                </label>
            </template>
            <?php $this->applyTemplateHook('search-custom-entity', 'begin') ?>
            <mc-tab icon="list" label="<?= $definition->text(i::__('Lista')) ?>" slug="list">
                <div class="search__tabs--list">
                    <search-list :pseudo-query="pseudoQuery" type="<?= $definition->slug ?>" select="name,shortDescription,files.avatar">
                        <template #filter>
                            <search-filter-custom-entity :pseudo-query="pseudoQuery" slug="<?= $definition->slug ?>"></search-filter-custom-entity>
                        </template>
                    </search-list>
                </div>
            </mc-tab>
            <?php $this->applyTemplateHook('search-custom-entity', 'end') ?>
        </mc-tabs>
        <?php $this->applyTemplateHook('search-custom-entity', 'after') ?>
    </template>
</search>