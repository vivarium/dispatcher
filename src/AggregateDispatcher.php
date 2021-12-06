<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher;

use Vivarium\Collection\Sequence\ArraySequence;
use Vivarium\Collection\Sequence\Sequence;

use function get_class;

final class AggregateDispatcher implements EventDispatcher
{
    /** @var Sequence<EventListenerProvider> */
    private Sequence $providers;

    /**
     * @no-named-arguments
     */
    public function __construct(EventListenerProvider ...$provider)
    {
        $this->providers = new ArraySequence(...$provider);
    }

    /**
     * @param T $event
     *
     * @return T
     *
     * @template T as Event
     */
    public function dispatch(Event $event)
    {
        /** @var Sequence<ListenerAndPriority<Event>> $listeners */
        $listeners = new ArraySequence();
        foreach ($this->providers as $provider) {
            foreach ($provider->provide(get_class($event)) as $listener) {
                $listeners = $listeners->add($listener);
            }
        }

        $listeners = $listeners->sort(new ListenerAndPriorityComparator());
        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }

            /**
             * phpcs:ignore
             * @var T $event
             */
            $event = $listener->getEventListener()->handle($event);
        }

        return $event;
    }
}
