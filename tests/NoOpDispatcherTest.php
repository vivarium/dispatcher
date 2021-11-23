<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher\Test;

use PHPUnit\Framework\TestCase;
use Vivarium\Dispatcher\Event;
use Vivarium\Dispatcher\NoOpDispatcher;

/**
 * @coversDefaultClass \Vivarium\Dispatcher\NoOpDispatcher
 */
final class NoOpDispatcherTest extends TestCase
{
    /**
     * @covers ::dispatch()
     */
    public function testDispatch(): void
    {
        $event       = $this->createMock(Event::class);
        $eventReturn = (new NoOpDispatcher())->dispatch($event);

        static::assertSame($event, $eventReturn);
    }
}
