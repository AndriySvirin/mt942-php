<?php

namespace EbicsApi\MT942\models;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Money specifies currency and amount.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class Money
{

    /**
     * Currency ISO code.
     *
     * @var string
     */
    private $currency;

    /**
     * Amount.
     *
     * @var float
     */
    private $amount;

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $value
     */
    public function setCurrency(string $value): void
    {
        $this->currency = $value;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $value
     */
    public function setAmount(float $value): void
    {
        $this->amount = $value;
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
        // Can have a currency. With fixed length and passed pattern.
        $metadata->addPropertyConstraints('currency', [
            new NotBlank(),
            new Length(['min' => 3, 'max' => 3]),
            new Type('string'),
            new Regex(['pattern' => '/^[A-Z]+$/']),
        ]);
        // Must have an amount.
        $metadata->addPropertyConstraints('amount', [
            new NotBlank(),
            new Length(['max' => 15]),
            new Type('float'),
        ]);
    }
}
