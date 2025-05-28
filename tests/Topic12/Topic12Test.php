<?php

declare(strict_types=1);

namespace App\Tests\Topic12;

use App\Service\Http\ExampleService;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Profiler\Profile;

/**
 * Test suite for Symfony Certification Topic 12 (Automated Tests).
 */
class Topic12Test extends WebTestCase
{
    /**
     * @see config/packages/framework.yaml
     */
    #[Test]
    public function profilerCanBeUsedToMeasureWebEndpointPerformance(): void
    {
        $client = static::createClient();
        $client->enableProfiler();

        $client->request('GET', '/topic3/router');

        $profile = $client->getProfile();
        $duration = $profile?->getCollector('time')?->getDuration();

        self::assertInstanceOf(Profile::class, $profile);
        self::assertNotNull($duration);
        self::assertLessThan(10000, $duration);
        self::assertResponseIsSuccessful();
    }

    #[Test]
    public function phpUnitDoublesCanBeUsedFakeComplexBehaviors(): void
    {
        $service = $this->createMock(ExampleService::class);
        $service->expects(self::exactly(2))
            ->method('execute')
            ->willReturnOnConsecutiveCalls(
                'result1',
                'result2'
            );

        $result1 = $service->execute();
        $result2 = $service->execute();

        self::assertSame('result1', $result1);
        self::assertSame('result2', $result2);
    }
}
