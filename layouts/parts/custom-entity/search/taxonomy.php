<?php

/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var MapasCulturais\Definitions\Taxonomy|null $taxonomy
 */

use MapasCulturais\i;

$this->import('
    custom-entity-taxonomy-filter
');

$json_taxonomy = json_encode($taxonomy, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>

<custom-entity-taxonomy-filter
    :pseudo-query="pseudoQuery"
    :taxonomy='<?= $json_taxonomy ?>'
></custom-entity-taxonomy-filter>