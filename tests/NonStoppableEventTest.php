<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher\Test;

use PHPUnit\Framework\TestCase;
use Vivarium\Dispatcher\Test\Stub\StubNonStoppableEvent;

/**
 * @coversDefaultClass \Vivarium\Dispatcher\NonStoppableEvent
 */
final class NonStoppableEventTest extends TestCase
{
    /**
     * @covers ::isPropagationStopped()
     */
    public function testPropagation(): void
    {
        $event = new StubNonStoppableEvent();

        static::assertFalse($event->isPropagationStopped());
    }
}
