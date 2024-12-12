<?php

namespace EbicsApi\MT942\models;

use EbicsApi\MT942\contracts\MarkInterface;
use DateTime;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Statement Line specifies main information for Statement.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class StatementLine implements MarkInterface
{

    /**
     * @var DateTime
     */
    private $valueDate;

    /**
     * @var string|null MMDD
     */
    private $entryDate;

    /**
     * @var string
     */
    private $mark;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $transactionTypeIdCode;

    /**
     * @var string
     */
    private $customerRef;

    /**
     * @return DateTime
     */
    public function getValueDate(): DateTime
    {
        return $this->valueDate;
    }

    /**
     * @param DateTime $valueDate
     */
    public function setValueDate(DateTime $valueDate): void
    {
        $this->valueDate = $valueDate;
    }

    /**
     * @return null|string
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * @param string|null $entryDate
     */
    public function setEntryDate(string $entryDate = null): void
    {
        $this->entryDate = $entryDate;
    }

    /**
     * @return string
     */
    public function getMark(): string
    {
        return $this->mark;
    }

    /**
     * @param string $mark
     */
    public function setMark(string $mark): void
    {
        $this->mark = $mark;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getTransactionTypeIdCode(): string
    {
        return $this->transactionTypeIdCode;
    }

    /**
     * @param string $transactionTypeIdCode
     */
    public function setTransactionTypeIdCode(string $transactionTypeIdCode): void
    {
        $this->transactionTypeIdCode = $transactionTypeIdCode;
    }

    /**
     * @return string
     */
    public function getCustomerRef(): string
    {
        return $this->customerRef;
    }

    /**
     * @param string $customerRef
     */
    public function setCustomerRef(string $customerRef): void
    {
        $this->customerRef = $customerRef;
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
        // Must have a valueDate. With specified type.
        $metadata->addPropertyConstraints('valueDate', [
            new NotBlank(),
            new Type('object'),
        ]);
        // Can have a entryDate. With specified type.
        $metadata->addPropertyConstraints('entryDate', [
            new Length(['min' => 4, 'max' => 4]),
            new Type('string'),
            new Regex(['pattern' => '/^\w+$/']),
        ]);
        // Must have a mark. From the listed options.
        $metadata->addPropertyConstraints('mark', [
            new Choice([self::MARK_DEBIT, self::MARK_CREDIT]),
        ]);
        // Must have an amount. From the listed options.
        $metadata->addPropertyConstraints('amount', [
            new NotBlank(),
            new Length(['max' => 15]),
            new Type('float'),
        ]);
        // Must have an transactionTypeIdCode. With fixed length.
        $metadata->addPropertyConstraints('transactionTypeIdCode', [
            new NotBlank(),
            new Length(['min' => 4, 'max' => 4]),
            new Regex(['pattern' => '/^[A-Z0-9]+$/']),
            new Type('string'),
        ]);
        // Must have an customerRef. With max length.
        $metadata->addPropertyConstraints('customerRef', [
            new NotBlank(),
            new Length(['max' => 16]),
            new Regex(['pattern' => '/^[A-Za-z0-9]+$/']),
            new Type('string'),
        ]);
    }
}
