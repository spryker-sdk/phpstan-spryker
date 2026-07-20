<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\ApiPlatformProviderProcessorRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\ApiPlatformProviderProcessorRule>
 */
class ApiPlatformProviderProcessorRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassNode>
     */
    protected function getRule(): Rule
    {
        return new ApiPlatformProviderProcessorRule($this->createReflectionProvider());
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../rule-test.neon'];
    }

    public function testGivenAProcessorMissingSerializerAndNullableReturnWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/ApiPlatformProviderProcessorRule/BadProcessor.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'API Platform state class Pyz\Glue\FixtureModule\Api\Storefront\Processor\BadProcessor must receive Symfony\Component\Serializer\SerializerInterface via its constructor.',
                10,
            ],
            [
                'API Platform processor Pyz\Glue\FixtureModule\Api\Storefront\Processor\BadProcessor::process() must declare an ?object (or object|null) return type.',
                20,
            ],
        ]);
    }

    public function testGivenAProviderMissingSerializerWhenAnalysedThenAnErrorIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/ApiPlatformProviderProcessorRule/BadProvider.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'API Platform state class Pyz\Glue\FixtureModule\Api\Storefront\Provider\BadProvider must receive Symfony\Component\Serializer\SerializerInterface via its constructor.',
                10,
            ],
        ]);
    }

    public function testGivenACompliantProcessorWhenAnalysedThenNothingIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/ApiPlatformProviderProcessorRule/GoodProcessor.php';

        // Act & Assert
        $this->analyse([$fixture], []);
    }
}
