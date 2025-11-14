<?php

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    confirm-before-exit
    entity-actions
    entity-header
    entity-status
    mc-breadcrumb
    mc-card
    mc-container
    mc-tab
    mc-tabs
');

$definition = Plugin::$intance->getEntityDefinition();

$this->breadcrumb = [
    ['label' => i::__('Painel'), 'url' => $app->createUrl('panel', 'index')],
    ['label' => $definition->text(i::__('Minhas Entidades'))],
    ['label' => $entity->name, 'url' => $app->createUrl($definition->slug, 'edit', [$entity->id])],
];

?>
<?php $this->applyTemplateHook('main-app','before') ?>
<div class="main-app">
    <?php $this->applyTemplateHook('main-app','begin') ?>
    <mc-breadcrumb></mc-breadcrumb>
    <?php $this->applyTemplateHook('header','before') ?>
    <entity-header :entity="entity" editable>
        <template #title-edit>
            <h2><?= $definition->text(i::__('Edição da entidade')) ?></h2>
        </template>
    </entity-header>
    <?php $this->applyTemplateHook('header','after') ?>

    <?php $this->applyTemplateHook('tabs','before') ?>
    <mc-tabs class="tabs" sync-hash>
        <?php $this->applyTemplateHook('tabs','begin') ?>
        <mc-tab label="<?= i::_e('Informações') ?>" slug="info">
            <?php $this->applyTemplateHook('tab-info','begin') ?>
            <mc-container>
                <entity-status :entity="entity"></entity-status>
                <mc-card class="feature">
                    <template #title>
                        <?php $this->applyTemplateHook('tab-info--title','begin') ?>
                        <label class="card__title--title"><?php i::_e("Informações de Apresentação") ?></label>
                        <p class="card__title--description"><?php i::_e("Os dados inseridos abaixo serão exibidos para todos os usuários") ?></p>
                        <?php $this->applyTemplateHook('tab-info--title','end') ?>
                    </template>
                    <template #content>
                        <?php $this->applyTemplateHook('tab-info--main','before') ?>
                        <div class="grid-12 v-center">
                            <?php $this->applyTemplateHook('tab-info--main','begin') ?>
                            <?php $this->applyTemplateHook('tab-info--main','end') ?>
                        </div>
                        <?php $this->applyTemplateHook('tab-info--main','after') ?>
                        <div class="divider"></div>
                        <div class="right">
                            <?php $this->applyTemplateHook('tab-info--aside','begin') ?>
                            <?php $this->applyTemplateHook('tab-info--aside','end') ?>
                        </div>
                    </template>
                </mc-card>
                <main>
                    <?php $this->applyTemplateHook('main-mc-card','begin') ?>
                    <?php foreach ($definition->editSections as $section_slug => $section_title): ?>
                        <mc-card>
                            <template #title>
                                <label><?= $section_title ?></label>
                            </template>
                            <template #content>
                                <?php $this->applyTemplateHook("tab-info--{$section_slug}",'before') ?>
                                <div class="grid-12">
                                    <?php $this->applyTemplateHook("tab-info--{$section_slug}",'begin') ?>
                                    <?php $this->applyTemplateHook("tab-info--{$section_slug}",'end') ?>
                                </div>
                                <?php $this->applyTemplateHook("tab-info--{$section_slug}",'after') ?>
                            </template>
                        </mc-card>
                    <?php endforeach; ?>
                    <?php $this->applyTemplateHook('main-mc-card','end') ?>
                </main>
                <aside>
                    <mc-card>
                        <template #content>
                            <div class="grid-12">

                            </div>
                        </template>
                    </mc-card>
                </aside>
            </mc-container>
            <?php $this->applyTemplateHook('tab-info','end') ?>
        </mc-tab>
        <?php $this->applyTemplateHook('tabs','end') ?>
    </mc-tabs>
    <?php $this->applyTemplateHook('tabs','after') ?>

    <entity-actions :entity="entity" editable></entity-actions>
    <?php $this->applyTemplateHook('main-app','end') ?>
</div>
<?php $this->applyTemplateHook('main-app','after') ?>
<confirm-before-exit :entity="entity"></confirm-before-exit>
