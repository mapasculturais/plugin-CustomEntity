<?php

namespace CustomEntity;

use \MapasCulturais\App;

class Position {

    function __construct(
        public readonly string $section = 'more-info',
        public readonly string $anchor = 'end',
        public readonly int $priority = 10,
    ) {
    }

    function templateHook(string $template, callable $callable) {
        $app = App::i();

        $app->hook("template({$template}.tab-info--{$this->section}):{$this->anchor}", $callable, $this->priority);
    }
}
