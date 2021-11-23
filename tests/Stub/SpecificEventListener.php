<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher\Test\Stub;

use Vivarium\Dispatcher\EventListener;
use Vivarium\Equality\Equality;
use Vivarium\Equality\EqualsBuilder;
use Vivarium\Equality\HashBuilder;

use function get_class;

/**
 * @template-implements EventListener<SpecificEvent>
 */
final class SpecificEventListener implements EventListener, Equality
{
    /**
     * @param SpecificEvent $event
     */
    public function handle($event): SpecificEvent
    {
        return $event;
    }

    /**
     * @psalm-mutation-free
     */
    public function equals(object $object): bool
    {
        return (new EqualsBuilder())
            ->append(self::class, get_class($object))
            ->isEquals();
    }

    /**
     * @psalm-mutation-free
     */
    public function hash(): string
    {
        return (new HashBuilder())
            ->append(self::class)
            ->getHashCode();
    }
}
