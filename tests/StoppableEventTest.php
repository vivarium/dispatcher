<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher\Test;

use PHPUnit\Framework\TestCase;
use Vivarium\Dispatcher\Test\Stub\StubStoppableEvent;

/**
 * @coversDefaultClass \Vivarium\Dispatcher\StoppableEvent
 */
final class StoppableEventTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::stopPropagation()
     * @covers ::isPropagationStopped()
     */
    public function testPropagation(): void
    {
        $event = new StubStoppableEvent();

        static::assertFalse($event->isPropagationStopped());

        $event->stopPropagation();

        static::assertTrue($event->isPropagationStopped());
    }
}
