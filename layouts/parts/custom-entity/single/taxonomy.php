<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var MapasCulturais\Definitions\Taxonomy $taxonomy
 */

$this->import('entity-terms');
?>
<entity-terms :entity="entity" classes="col-12" taxonomy="<?= $taxonomy->slug ?>" title="<?= $taxonomy->description ?>"></entity-terms>
