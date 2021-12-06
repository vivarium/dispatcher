<?php

declare(strict_types=1);

/*
 * This file is part of Vivarium
 * SPDX-License-Identifier: MIT
 * Copyright (c) 2021 Luca Cantoreggi
 */

namespace Vivarium\Dispatcher;

/**
 * @template T as Event
 * @template-implements EventListener<T>
 */
final class ClosureListener implements EventListener
{
    /** @var callable(T): T */
    private $closure;

    /**
     * @param callable(T): T $closure
     */
    public function __construct(callable $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param T $event
     *
     * @return T
     */
    public function handle($event)
    {
        return ($this->closure)($event);
    }
}
