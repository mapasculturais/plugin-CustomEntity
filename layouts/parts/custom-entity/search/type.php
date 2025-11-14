<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 * @var CustomEntity\EntityDefinition $definition
 * @var array<int|string, string> $types
 */

$this->import('
    custom-entity-type-filter
');

$normalized_types = [];

foreach ($types as $id => $label) {
    $normalized_types[] = [
        'value' => $id,
        'label' => $label,
    ];
}

$json_types = json_encode($normalized_types, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>

<custom-entity-type-filter :pseudo-query="pseudoQuery" :types='<?= $json_types ?>'></custom-entity-type-filter>

