<?php 
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use CustomEntity\Plugin;
use MapasCulturais\i;

$this->import('
    entity-field 
    entity-terms
    mc-link
    mc-modal 
'); 

$definition = Plugin::$intance->getEntityDefinition();

?>
<mc-modal :title="modalTitle" classes="create-modal create-custom-entity-modal" button-label="<?= $definition->text(i::__('Criar Entidade')) ?>"  @open="createEntity()" @close="destroyEntity()">
    <template v-if="entity && !entity.id" #default>
        <label><?= $definition->text(i::__('Crie uma entidade com informações básicas'))?><br><?php i::_e('e de forma rápida')?></label>
        <?php $this->applyTemplateHook("create-{$definition->slug}__fields", 'before'); ?>
        <div class="create-modal__fields">
            <?php $this->applyTemplateHook("create-{$definition->slug}__fields", 'begin'); ?>
            <?php $this->applyTemplateHook("create-{$definition->slug}__fields", 'end'); ?>
        </div>
        <?php $this->applyTemplateHook("create-{$definition->slug}__fields", 'after'); ?>
    </template>
    
    <template v-if="entity?.id" #default>
        <label><?= $definition->text(i::__('Você pode completar as informações do sua entidade agora ou pode deixar para depois.'));?></label><br><br>
        <label><?= $definition->text(i::__('Para completar e publicar seu nova entidade, acesse a área <b>Rascunhos</b> em <b>Minhas Entidades</b> no <b>Painel de Controle</b>.'));?></label>
            
    </template>

    <template #button="modal">
        <slot :modal="modal"></slot>
    </template>

    <template v-if="!entity?.id" #actions="modal">
        <button class="button button--primary" @click="createPublic(modal)"><?php i::_e('Criar e Publicar')?></button>
        <button class="button button--solid-dark" @click="createDraft(modal)"><?php i::_e('Criar em Rascunho')?></button>
        <button class="button button--text button--text-del " @click="modal.close()"><?php i::_e('Cancelar')?></button>
    </template>
    <template v-if="entity?.id && entity.status==1" #actions="modal">
        <mc-link :entity="entity" class="button button--primary-outline button--icon"><?= $definition->text(i::__('Ver Entidade'));?></mc-link>
        <button class="button button--secondarylight button--icon " @click="modal.close()"><?php i::_e('Completar Depois')?></button>
        <mc-link :entity="entity" route='edit' class="button button--primary button--icon"><?php i::_e('Completar Informações')?></mc-link>
     </template>
     <template v-if="entity?.id && entity.status==0" #actions="modal">
        <mc-link :entity="entity" class="button button--primary-outline button--icon"><?= $definition->text(i::__('Ver Entidade'));?></mc-link>
        <button class="button button--secondarylight button--icon " @click="modal.close()"><?php i::_e('Completar Depois')?></button>
        <mc-link :entity="entity" route='edit' class="button button--primary button--icon"><?php i::_e('Completar Informações')?></mc-link>
    </template>
</mc-modal>
