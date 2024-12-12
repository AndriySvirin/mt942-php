<?php

namespace EbicsApi\MT942\models;

use EbicsApi\MT942\MT942Validator;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Account Identification specifies Bank Person Account.
 * Can have one of format Intentional or Internal.
 * @see IBAN standard.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class AccountIdentification
{

    /**
     * Types.
     */
    const FORMAT_BAN = 'BAN';
    const FORMAT_IBAN = 'IBAN';

    /**
     * Format.
     * There format IBAN is described by IBAN.
     * There format BAN is described by Bank and its Account.
     *
     * @var string
     */
    private $format;

    /**
     * IBAN country code.
     * @var string
     */
    private $ibanCountryCode;

    /**
     * IBAN control code.
     *
     * @var string
     */
    private $ibanControlCode;
    /**
     * IBAN Basic Bank Account Number.
     *
     * @var string
     */
    private $ibanBBAN;

    /**
     * Bank Identifier Code.
     *
     * @var string
     */
    private $bic;

    /**
     * Account Number.
     *
     * @var string
     */
    private $accNr;

    public function setTypeBAN(): void
    {
        $this->format = self::FORMAT_BAN;
    }

    public function setTypeIBAN(): void
    {
        $this->format = self::FORMAT_IBAN;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getIBANCountryCode(): string
    {
        return $this->ibanCountryCode;
    }

    /**
     * @param string $value
     */
    public function setIBANCountryCode(string $value): void
    {
        $this->ibanCountryCode = $value;
    }

    /**
     * @return string
     */
    public function getIBANControlCode(): string
    {
        return $this->ibanControlCode;
    }

    /**
     * @param string $value
     */
    public function setIBANControlCode(string $value): void
    {
        $this->ibanControlCode = $value;
    }

    /**
     * @return string
     */
    public function getIBANBBAN(): string
    {
        return $this->ibanBBAN;
    }

    /**
     * @param string $value
     */
    public function setIBANBBAN(string $value): void
    {
        $this->ibanBBAN = $value;
    }

    /**
     * @return string
     */
    public function getBIC(): string
    {
        return $this->bic;
    }

    /**
     * @param string $value
     */
    public function setBIC(string $value): void
    {
        $this->bic = $value;
    }

    /**
     * @return string
     */
    public function getAccNr(): string
    {
        return $this->accNr;
    }

    /**
     * @param string $value
     */
    public function setAccNr(string $value): void
    {
        $this->accNr = $value;
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
        // Must have a format. From the listed options.
        $metadata->addPropertyConstraints('format', [
            new NotBlank(),
            new Choice([self::FORMAT_BAN, self::FORMAT_IBAN])
        ]);
        // If format is IBAN, then ibanCountryCode must present and has specific length and pass character mask.
        $metadata->addPropertyConstraints('ibanCountryCode', [
            new NotBlank(['groups' => [self::FORMAT_IBAN]]),
            new Length(['min' => 2, 'max' => 2]),
            new Type('string'),
            new Regex(['pattern' => '/^[A-Za-z]+$/']),
        ]);
        // If format is IBAN, then ibanControlCode must present and has specific length and pass character mask.
        $metadata->addPropertyConstraints('ibanControlCode', [
            new NotBlank(['groups' => [self::FORMAT_IBAN]]),
            new Length(['min' => 2, 'max' => 2]),
            new Type(['type' => 'string']),
        ]);
        // If format is IBAN, then ibanBBAN must present and has specific length and pass character mask.
        $metadata->addPropertyConstraints('ibanBBAN', [
            new NotBlank(['groups' => [self::FORMAT_IBAN]]),
            new Length(['min' => 24, 'max' => 24]),
            new Type(['type' => 'string']),
        ]);
        // If format is BAN, then bic must present and has specific length and pass character mask.
        $metadata->addPropertyConstraints('bic', [
            new NotBlank(['groups' => [self::FORMAT_BAN]]),
            new Length(['min' => 8, 'max' => 8]),
            new Type('string'),
            new Regex(['pattern' => '/^\w+$/']),
        ]);
        // If format is BAN, then accNr must present and has specific length and pass character mask.
        $metadata->addPropertyConstraints('accNr', [
            new NotBlank(['groups' => [self::FORMAT_BAN]]),
            new Length(['max' => 12]),
            new Type('string'),
            new Regex(['pattern' => '/^\d+$/', 'groups' => [self::FORMAT_BAN]]),
        ]);
    }
}
