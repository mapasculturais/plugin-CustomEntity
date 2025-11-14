<?php

namespace CustomEntity\Traits;

use CustomEntity\EntityDefinition;
use CustomEntity\Position;

/**
 * @property-read Position $editPosition
 * @property-read Position $singlePosition
 */
trait PartPosition {
    private readonly ?Position $_editPosition;
    private readonly ?Position $_singlePosition;

    protected function getDefaultEditPosition(): Position {
        return new Position('more-info', 'end', 10);
    }

    protected function getDefaultSinglePosition(): Position {
        return new Position('more-info', 'end', 10);
    }

    public function getEditPosition(): Position {
        return $this->_editPosition ?? $this->getDefaultEditPosition();
    }

    public function getSinglePosition(): Position {
        return $this->_singlePosition ?? $this->getDefaultSinglePosition();
    }

    public function editPosition(string $section = 'more-info', string $anchor = 'end', int $priority = 10): static {
        $this->_editPosition = new Position($section, $anchor, $priority);
        return $this;
    }

    public function singlePosition(string $section = 'more-info', string $anchor = 'end', int $priority = 10): static {
        $this->_singlePosition = new Position($section, $anchor, $priority);
        return $this;
    }

    public function editTemplateHook(EntityDefinition $entityDefinition, callable $callable): void {
        $this->editPosition->templateHook("{$entityDefinition->slug}.edit", $callable);
    }

    public function singleTemplateHook(EntityDefinition $entityDefinition, callable $callable): void {
        $this->singlePosition->templateHook("{$entityDefinition->slug}.single", $callable);
    }
}
