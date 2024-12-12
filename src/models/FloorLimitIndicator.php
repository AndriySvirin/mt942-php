<?php

namespace EbicsApi\MT942\models;

use EbicsApi\MT942\contracts\MarkInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Floor Limit Indicator specifies the payment information.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class FloorLimitIndicator implements MarkInterface
{

    /**
     * Debit or Credit mark.
     *
     * @var string|null [D|C]
     */
    private $mark;

    /**
     * @var Money
     */
    private $money;

    public function __construct()
    {
        $this->money = new Money();
    }

    /**
     * @return null|string
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param string|null $value
     */
    public function setMark(string $value = null): void
    {
        $this->mark = $value;
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {
        return $this->money;
    }

    /**
     * Validation rules.
     *
     * @param ClassMetadata $metadata
     *
     * @see MT942Validator::getValidator()
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        // Can have a mark. From the listed options.
        $metadata->addPropertyConstraints('mark', [
            new Choice([self::MARK_DEBIT, self::MARK_CREDIT]),
        ]);
        // Must have a valid money.
        $metadata->addPropertyConstraints('money', [
            new Valid(),
            new NotBlank(),
            new Type('object'),
        ]);
    }
}
