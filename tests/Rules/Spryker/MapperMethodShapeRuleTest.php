<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerSdk\PHPStanSpryker\Test\Rules\Spryker;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use SprykerSdk\PHPStanSpryker\Rules\Spryker\MapperMethodShapeRule;

/**
 * @extends \PHPStan\Testing\RuleTestCase<\SprykerSdk\PHPStanSpryker\Rules\Spryker\MapperMethodShapeRule>
 */
class MapperMethodShapeRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule<\PHPStan\Node\InClassMethodNode>
     */
    protected function getRule(): Rule
    {
        return new MapperMethodShapeRule();
    }

    /**
     * @return void
     */
    public function testGivenAPersistenceMapperWithMisnamedAndUnderparameterisedMethodsWhenAnalysedThenErrorsAreReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/MapperMethodShapeRule/CommentMapper.php';

        // Act & Assert
        $this->analyse([$fixture], [
            [
                'Persistence mapper Pyz\Zed\FixtureModule\Persistence\Propel\Mapper\CommentMapper declares public method convertComment(); mapper methods must be named map[Source]To[Target] (.claude/rules/mapper-pattern.md).',
                19,
            ],
            [
                'Persistence mapper method Pyz\Zed\FixtureModule\Persistence\Propel\Mapper\CommentMapper::convertComment() takes fewer than 2 parameters; mappers must accept both the source and the caller-provided target object (.claude/rules/mapper-pattern.md).',
                19,
            ],
            [
                'Persistence mapper method Pyz\Zed\FixtureModule\Persistence\Propel\Mapper\CommentMapper::mapComment() takes fewer than 2 parameters; mappers must accept both the source and the caller-provided target object (.claude/rules/mapper-pattern.md).',
                24,
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testGivenABusinessMapperWhenAnalysedThenNothingIsReported(): void
    {
        // Arrange
        $fixture = __DIR__ . '/../../Fixtures/MapperMethodShapeRule/BusinessMapper.php';

        // Act & Assert
        $this->analyse([$fixture], []);
    }
}
