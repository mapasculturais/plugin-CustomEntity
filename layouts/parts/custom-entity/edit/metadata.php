<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

$this->import('entity-field');

?>
<div class="col-12 grid-12">
    <entity-field :entity="entity" classes="col-12" prop="<?= $definition->key ?>"></entity-field>
</div>

