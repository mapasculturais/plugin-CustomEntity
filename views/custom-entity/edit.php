<?php

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->layout = 'entity';

$this->import('
    mc-breadcrumb
    entity-header
    mc-tabs
    mc-tab
    mc-card
    entity-status
    mc-container

    country-address-form
    confirm-before-exit 
    entity-actions
    entity-admins
    entity-cover
    entity-field
    entity-files-list
    entity-gallery
    entity-gallery-video
    entity-links
    entity-owner
    entity-parent-edit
    entity-profile
    entity-related-agents
    entity-social-media
    entity-terms
    permission-publish
');

$this->breadcrumb = [
    ['label' => i::__('Painel'), 'url' => $app->createUrl('panel', 'index')],
    ['label' => i::__('Meus Espaços'), 'url' => $app->createUrl('panel', 'spaces')],
    ['label' => $entity->name, 'url' => $app->createUrl('space', 'edit', [$entity->id])],
];

$definition = Plugin::$intance;

?>

<?php $this->applyTemplateHook('main-app','before') ?>
<div class="main-app">
    <?php $this->applyTemplateHook('main-app','begin') ?>
    <mc-breadcrumb></mc-breadcrumb>
    <?php $this->applyTemplateHook('header','before') ?>
    <entity-header :entity="entity" editable></entity-header>
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
                        <?php $this->applyTemplateHook('tab-info--content','before') ?>
                        <div class="grid-12 v-center">
                            <?php $this->applyTemplateHook('tab-info--content','begin') ?>
                            <?php $this->applyTemplateHook('tab-info--content','end') ?>
                        </div>
                        <?php $this->applyTemplateHook('tab-info--content','after') ?>
                        <div class="divider"></div>
                        <div class="right">
                            <?php $this->applyTemplateHook('tab-info--content--right','begin') ?>
                            <?php $this->applyTemplateHook('tab-info--content--right','end') ?>
                        </div>
                    </template>
                </mc-card>
                <main>
                    <?php $this->applyTemplateHook('main-mc-card','begin') ?>
                    <mc-card>
                        <template #title>
                            <label><?php i::_e("Endereço do espaço"); ?></label>
                        </template>
                        <template #content>
                            <?php $this->applyTemplateHook('mc-card-content-address','begin') ?>
                            <div class="grid-12">
                                
                            </div>
                            <?php $this->applyTemplateHook('mc-card-content-address','end') ?>
                        </template>
                    </mc-card>
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
