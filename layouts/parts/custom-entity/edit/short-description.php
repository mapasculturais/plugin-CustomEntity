<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;

$this->import('entity-field');
?>
<div class="col-12 grid-12">
    <entity-field :entity="entity" classes="col-12" prop="shortDescription" :max-length="400"></entity-field>
</div>