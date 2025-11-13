<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */
use MapasCulturais\i;

?>
<div v-if="entity.longDescription" class="col-12">
    <h2><?php i::_e('Descrição Detalhada'); ?></h2>
    <p class="description"  v-html="entity.longDescription"></p>
</div>