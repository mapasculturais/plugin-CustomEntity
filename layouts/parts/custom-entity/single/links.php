<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var MapasCulturais\Definitions\Taxonomy $taxonomy
 */
use MapasCulturais\i;

$this->import('entity-links');
?>
<entity-links :entity="entity" classes="col-12" title="<?php i::_e('Links'); ?>"></entity-links>
