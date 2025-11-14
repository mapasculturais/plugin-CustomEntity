<?php

namespace CustomEntity;

class Position {

    function __construct(
        public readonly string $section = 'info',
        public readonly int $priority = 10,
    ) {
    }
}
