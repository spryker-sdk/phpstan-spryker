<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\GatewayControllerActionSignatureRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\GatewayControllerActionSignatureRule>
 */
class GatewayControllerActionSignatureRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
     */
    protected function getRule(): Rule
    {
        return new GatewayControllerActionSignatureRule($this->createReflectionProvider());
    }

    /**
     * @return list<string>
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../rule-test.neon'];
    }

    public function testGivenGatewayActionsWithoutTransferInAndOutWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/GatewayControllerActionSignatureRule/GatewayController.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Gateway action Pyz\Zed\FixtureModule\Communication\Controller\GatewayController::badParamAction() must accept exactly one transfer object parameter and declare a transfer (or nullable transfer) return type.',
                27,
            ],
            [
                'Gateway action Pyz\Zed\FixtureModule\Communication\Controller\GatewayController::badParamCountAction() must accept exactly one transfer object parameter and declare a transfer (or nullable transfer) return type.',
                32,
            ],
            [
                'Gateway action Pyz\Zed\FixtureModule\Communication\Controller\GatewayController::badReturnAction() must accept exactly one transfer object parameter and declare a transfer (or nullable transfer) return type.',
                37,
            ],
            [
                'Gateway action Pyz\Zed\FixtureModule\Communication\Controller\GatewayController::noReturnTypeAction() must accept exactly one transfer object parameter and declare a transfer (or nullable transfer) return type.',
                42,
            ],
        ]);
    }

    public function testGivenANonGatewayControllerWhenAnalysedThenNothingIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/GatewayControllerActionSignatureRule/RegularController.php';

        // Act & Assert
        $this->analyse([$fixture], []);
    }
}
