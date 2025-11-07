<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->import('
    create-custom-entity
    panel--entity-card 
    panel--entity-tabs 
');

$definition = Plugin::$intance->getEntityDefinition();

?>
<div class="panel-page">
    <header class="panel-page__header">
        <div class="panel-page__header-title">
            <div class="title">
                <div class="title__icon <?= $definition->slug ?>__background"> <mc-icon name="<?= $definition->slug ?>"></mc-icon> </div>
                <h1 class="title__title"> <?= $definition->text(i::__('Minhas entidades')) ?> </h1>
            </div>
        </div>
        <p class="panel-page__header-subtitle">
            <?= $definition->text(i::__('Nesta seção você pode adicionar e gerenciar suas entidades')) ?>
        </p>
        <div class="panel-page__header-actions">
            <create-custom-entity :editable="true" #default="{modal}">
                <button @click="modal.open()" class="button button--primary button--icon">
                    <mc-icon name="add"></mc-icon>
                    <span><?= $definition->text(i::__('Criar Entidade')) ?></span>
                </button>
            </create-custom-entity>
        </div>
    </header>

    <panel--entity-tabs type="<?= $definition->slug ?>"></panel--entity-tabs>
</div>