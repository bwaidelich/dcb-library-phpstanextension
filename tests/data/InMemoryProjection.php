<?php

declare(strict_types=1);

namespace Wwwision\DCBLibraryPHPStanExtension\Tests;

use Wwwision\DCBEventStore\Types\Tag;
use Wwwision\DCBEventStore\Types\Tags;
use Wwwision\DCBLibrary\DomainEvent;
use Wwwision\DCBLibrary\Projection\InMemoryProjection;
use function PHPStan\Testing\assertType;

final class FooEvent implements DomainEvent {

    public function tags(): Tags
    {
        return Tags::single('foo', 'bar');
    }
}

final class BarEvent implements DomainEvent {

    public function tags(): Tags
    {
        return Tags::single('foo', 'bar');
    }
}


$stringProjection = InMemoryProjection::create(
    Tags::single('foo', 'bar'),
    [
        FooEvent::class => static fn (string $state, FooEvent $event): string => $state . '.',
        BarEvent::class => static fn (string $state, BarEvent $event): string => $state . '-',
    ],
    '',
);
assertType('Wwwision\DCBLibrary\Projection\InMemoryProjection<string>', $stringProjection);

$intProjection = InMemoryProjection::create(
    Tag::create('foo', 'bar'),
    [
        FooEvent::class => static fn (int $state, FooEvent $event): int => $state + 1,
        BarEvent::class => static fn (int $state, BarEvent $event): int => $state - 1,
    ],
    0
);
assertType('Wwwision\DCBLibrary\Projection\InMemoryProjection<int>', $intProjection);