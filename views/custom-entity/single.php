<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    entity-actions
    entity-data
    entity-header
    mc-breadcrumb
    mc-container
    mc-tab
    mc-tabs
');

$definition = Plugin::$intance->getEntityDefinition();

$this->breadcrumb = [
    ['label' => $definition->text(i::__('Entidades'))],
    ['label' => $entity->name, 'url' => $app->createUrl($definition->slug, 'single', [$entity->id])],
];
?>
<?php $this->applyTemplateHook('main-app','before') ?>
<div class="main-app">
    <?php $this->applyTemplateHook('main-app','begin') ?>
    <mc-breadcrumb></mc-breadcrumb>
    <?php $this->applyTemplateHook('header','before') ?>
    <entity-header :entity="entity">
        <template #metadata>
            <?php $this->applyTemplateHook('header-metadata','begin') ?>
            <dl v-if="entity.id && global.showIds[entity.__objectType]" class="metadata__id">
                <entity-data class="metadata__id" :entity="entity" prop="id" label="<?php i::_e("ID:")?>"></entity-data>
            </dl>
            <?php $this->applyTemplateHook('header-metadata','end') ?>
        </template>
    </entity-header>
    <?php $this->applyTemplateHook('header','after') ?>

    <?php $this->applyTemplateHook('tabs','before') ?>
    <mc-tabs class="tabs" sync-hash>
        <?php $this->applyTemplateHook('tabs','begin') ?>
        <mc-tab icon="exclamation" label="<?= i::_e('Informações') ?>" slug="info">
            <?php $this->applyTemplateHook('tab-info','begin') ?>
            <div class="tabs__info">
                <mc-container>
                    <?php $this->applyTemplateHook('tab-info--container','begin') ?>

                    <?php $this->applyTemplateHook('tab-info--main','before') ?>
                    <main class="grid-12">
                        <?php $this->applyTemplateHook('tab-info--main','begin') ?>
                        <?php $this->applyTemplateHook('tab-info--main','end') ?>
                    </main>
                    <?php $this->applyTemplateHook('tab-info--main','after') ?>

                    <?php $this->applyTemplateHook('tab-info--aside','before') ?>
                    <aside class="grid-12">
                        <?php $this->applyTemplateHook('tab-info--aside','begin') ?>
                        <?php $this->applyTemplateHook('tab-info--aside','end') ?>
                    </aside>
                    <?php $this->applyTemplateHook('tab-info--aside','after') ?>

                    <?php $this->applyTemplateHook('tab-info--container', 'end') ?>
                </mc-container>

                <mc-container>
                    <?php $this->applyTemplateHook('tab-info--sections','before') ?>
                    <div class="entity-sections">
                        <?php $this->applyTemplateHook('tab-info--sections','begin') ?>

                        <?php foreach($definition->singleSections as $section_slug => $section_title): ?>
                            <?php $this->applyTemplateHook("tab-info--{$section_slug}",'before') ?>
                            <section class="grid-12">
                                <h2 class="col-12"><?= $section_title ?></h2>
                                <?php $this->applyTemplateHook("tab-info--{$section_slug}",'begin') ?>
                                <?php $this->applyTemplateHook("tab-info--{$section_slug}",'end') ?>
                            </section>
                            <?php $this->applyTemplateHook("tab-info--{$section_slug}",'after') ?>
                        <?php endforeach; ?>

                        <?php $this->applyTemplateHook('tab-info--sections','end') ?>
                    </div>
                    <?php $this->applyTemplateHook('tab-info--sections','after') ?>

                    <?php $this->applyTemplateHook('tab-info--bottom-aside','before') ?>
                    <aside class="grid-12">
                        <?php $this->applyTemplateHook('tab-info--bottom-aside','begin') ?>
                        <?php $this->applyTemplateHook('tab-info--bottom-aside','end') ?>
                    </aside>
                    <?php $this->applyTemplateHook('tab-info--bottom-aside','after') ?>

                    <?php $this->applyTemplateHook('tab-info--sections', 'end') ?>
                </mc-container>
            </div>
            <?php $this->applyTemplateHook('tab-info','end') ?>
        </mc-tab>
        <?php $this->applyTemplateHook('tabs','end') ?>
    </mc-tabs>
    <?php $this->applyTemplateHook('tabs','after') ?>

    <entity-actions :entity="entity"></entity-actions>
    <?php $this->applyTemplateHook('main-app','end') ?>
</div>
<?php $this->applyTemplateHook('main-app','after') ?>
