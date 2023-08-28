<?php

declare(strict_types=1);

namespace Wwwision\DCBLibraryPHPStanExtension\Tests;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Wwwision\DCBLibrary\Projection\CompositeProjection;
use Wwwision\DCBLibrary\Projection\InMemoryProjection;

#[CoversClass(InMemoryProjection::class)]
#[CoversClass(CompositeProjection::class)]
final class CompositeProjectionExtensionTest extends TypeInferenceTestCase
{
    /**
     * @return iterable<mixed>
     */
    public static function dataFileAsserts(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__ . '/data/InMemoryProjection.php');
        yield from self::gatherAssertTypes(__DIR__ . '/data/CompositeProjection.php');
    }

    /**
     * @dataProvider dataFileAsserts
     */
    public function testFileAsserts(string $assertType, string $file, mixed ...$args)
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../extension.neon'];
    }
}