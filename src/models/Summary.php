<?php

namespace EbicsApi\MT942\models;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Summary for transaction operations.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class Summary
{

    /**
     * Number of entries.
     *
     * @var int|null
     */
    private $entriesNr;

    /**
     * Sum of entries.
     * @var Money
     */
    private $money;

    public function __construct()
    {
        $this->money = new Money();
    }

    /**
     * @return null|int
     */
    public function getEntriesNr()
    {
        return $this->entriesNr;
    }

    /**
     * @param int|null $value
     */
    public function setEntriesNr(int $value = null): void
    {
        $this->entriesNr = $value;
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {
        return $this->money;
    }

    /**
     * @param Money $value
     */
    public function setMoney(Money $value): void
    {
        $this->money = $value;
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
        // Can have a entriesNr. With max length and passed pattern.
        $metadata->addPropertyConstraints('entriesNr', [
            new Length(['max' => 5]),
            new Type('int'),
        ]);
        // Must have a valid money.
        $metadata->addPropertyConstraints('money', [
            new Valid(),
            new NotBlank(),
            new Type('object'),
        ]);
    }
}
