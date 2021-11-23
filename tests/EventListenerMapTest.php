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
use Vivarium\Dispatcher\EventListenerMap;
use Vivarium\Dispatcher\Priority;
use Vivarium\Dispatcher\Test\Stub\GenericEvent;
use Vivarium\Dispatcher\Test\Stub\GenericEventListener;
use Vivarium\Dispatcher\Test\Stub\SpecificEvent;
use Vivarium\Dispatcher\Test\Stub\SpecificEventListener;

/**
 * @coversDefaultClass \Vivarium\Dispatcher\EventListenerMap
 */
final class EventListenerMapTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::ofListeners()
     * @covers ::createMap
     * @covers ::provide()
     */
    public function testProvideOnEmpty(): void
    {
        $eventListenerMap = new EventListenerMap();

        $listeners = $eventListenerMap->provide(Event::class);

        static::assertTrue($listeners->isEmpty());
    }

    /**
     * @covers ::__construct()
     * @covers ::ofListeners()
     * @covers ::createMap
     * @covers ::collectClasses()
     * @covers ::provide()
     * @covers ::subscribe()
     */
    public function testProvideWithSubscription(): void
    {
        $eventListenerMap = new EventListenerMap();
        $eventListenerMap = $eventListenerMap->subscribe(GenericEvent::class, new GenericEventListener())
                                             ->subscribe(GenericEvent::class, new GenericEventListener())
                                             ->subscribe(GenericEvent::class, new GenericEventListener());

        $listeners = $eventListenerMap->provide(GenericEvent::class);

        static::assertCount(3, $listeners);
    }

    /**
     * @covers ::__construct()
     * @covers ::ofListeners()
     * @covers ::createMap
     * @covers ::collectClasses()
     * @covers ::provide()
     * @covers ::subscribe()
     */
    public function testProvideWithHierarchicalSubscription(): void
    {
        $eventListenerMap = new EventListenerMap();

        $eventListenerMap = $eventListenerMap->subscribe(GenericEvent::class, new GenericEventListener())
                                             ->subscribe(SpecificEvent::class, new SpecificEventListener());

        $genericListeners  = $eventListenerMap->provide(GenericEvent::class);
        $specificListeners = $eventListenerMap->provide(SpecificEvent::class);

        static::assertCount(1, $genericListeners);
        static::assertCount(2, $specificListeners);
    }

    /**
     * @covers ::__construct()
     * @covers ::ofListeners()
     * @covers ::createMap
     * @covers ::collectClasses()
     * @covers ::provide()
     * @covers ::subscribe()
     * @covers ::unsubscribe()
     */
    public function testProvideWithUnsubscribeListener(): void
    {
        $eventListenerMap = new EventListenerMap();

        $eventListenerMap = $eventListenerMap->subscribe(GenericEvent::class, new GenericEventListener())
                                             ->subscribe(SpecificEvent::class, new SpecificEventListener())
                                             ->subscribe(SpecificEvent::class, new SpecificEventListener());

        $eventListenerMap = $eventListenerMap->unsubscribe(SpecificEvent::class, new SpecificEventListener());

        $genericListeners  = $eventListenerMap->provide(GenericEvent::class);
        $specificListeners = $eventListenerMap->provide(SpecificEvent::class);

        static::assertCount(1, $genericListeners);
        static::assertCount(2, $specificListeners);
    }

    /**
     * @covers ::__construct()
     * @covers ::ofListeners()
     * @covers ::createMap
     * @covers ::collectClasses()
     * @covers ::provide()
     * @covers ::subscribe()
     * @covers ::unsubscribe()
     */
    public function testProvideWithUnsubscribedHandlersWithPriority(): void
    {
        $eventListenerMap = new EventListenerMap();

        $eventListenerMap = $eventListenerMap
            ->subscribe(GenericEvent::class, new GenericEventListener())
            ->subscribe(SpecificEvent::class, new SpecificEventListener(), Priority::VERY_LOW)
            ->subscribe(SpecificEvent::class, new SpecificEventListener());

        $eventListenerMap = $eventListenerMap
            ->unsubscribe(SpecificEvent::class, new SpecificEventListener(), Priority::VERY_LOW);

        $genericListeners  = $eventListenerMap->provide(GenericEvent::class);
        $specificListeners = $eventListenerMap->provide(SpecificEvent::class);

        static::assertCount(1, $genericListeners);
        static::assertCount(2, $specificListeners);
    }

    /**
     * @covers ::subscribe()
     */
    public function testSubscribeImmutability(): void
    {
        $eventListenerMap = new EventListenerMap();

        static::assertNotSame(
            $eventListenerMap,
            $eventListenerMap->subscribe(GenericEvent::class, new GenericEventListener())
        );
    }

    /**
     * @covers ::unsubscribe()
     */
    public function testUnsubscribeImmutability(): void
    {
        $eventListenerMap = new EventListenerMap();
        $eventListenerMap = $eventListenerMap->subscribe(GenericEvent::class, new GenericEventListener());

        static::assertNotSame(
            $eventListenerMap,
            $eventListenerMap->unsubscribe(GenericEvent::class, new GenericEventListener())
        );
    }
}
