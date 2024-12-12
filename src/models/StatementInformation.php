<?php

namespace EbicsApi\MT942\models;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Statement information specifies multiple additional options for Statement for account owner purpose.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class StatementInformation
{

    const LINE_00 = '00';
    const LINE_10 = '10';
    const LINE_20 = '20';
    const LINE_21 = '21';
    const LINE_22 = '22';
    const LINE_23 = '23';
    const LINE_24 = '24';
    const LINE_25 = '25';
    const LINE_26 = '26';
    const LINE_27 = '27';
    const LINE_28 = '28';
    const LINE_29 = '29';
    const LINE_30 = '30';
    const LINE_31 = '31';
    const LINE_32 = '32';
    const LINE_33 = '33';
    const LINE_38 = '38';

    /**
     * Identification code.
     * Business Transaction code.
     * @var string
     */
    private $idCode;

    /**
     * Lines of the information.
     * @var array
     */
    private $lines = [];

    /**
     * @return array
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @param string $nr
     * @param string $line
     */
    public function addLine(string $nr, string $line): void
    {
        $this->lines[$nr] = $line;
    }

    /**
     * @return string
     */
    public function getIdCode(): string
    {
        return $this->idCode;
    }

    /**
     * @param string $idCode
     */
    public function setIdCode(string $idCode): void
    {
        $this->idCode = $idCode;
    }

    /**
     * Booking text.
     * Transaction Description – Payment Origin (according to transaction code).
     * @return string
     */
    public function getLine00()
    {
        return $this->lines[self::LINE_00] ?? null;
    }

    /**
     *
     * @return string
     */
    public function getLine10()
    {
        return $this->lines[self::LINE_10] ?? null;
    }

    /**
     * Debit / Credit Account/Bank Code with leading zeroes (UD: / UK:).
     * @return string
     */
    public function getLine20()
    {
        return $this->lines[self::LINE_20] ?? null;
    }

    /**
     * Number of transaction.
     * @return string
     */
    public function getLine21()
    {
        return $this->lines[self::LINE_21] ?? null;
    }

    /**
     * Client`s Information (second line).
     * @return string
     */
    public function getLine22()
    {
        return $this->lines[self::LINE_22] ?? null;
    }

    /**
     * Client`s Information (third line).
     * @return string
     */
    public function getLine23()
    {
        return $this->lines[self::LINE_23] ?? null;
    }

    /**
     * Advice for the Beneficiary - First 27 characters.
     * @return string
     */
    public function getLine24()
    {
        return $this->lines[self::LINE_24] ?? null;
    }

    /**
     * Advice for the Beneficiary - Next 27 characters.
     * @return string
     */
    public function getLine25()
    {
        return $this->lines[self::LINE_25] ?? null;
    }

    /**
     * Advice for the Beneficiary - Next 27 characters.
     * @return string
     */
    public function getLine26()
    {
        return $this->lines[self::LINE_26] ?? null;
    }

    /**
     * Advice for the Beneficiary - Next 27 characters.
     * @return string
     */
    public function getLine27()
    {
        return $this->lines[self::LINE_27] ?? null;
    }

    /**
     * Advice for the Beneficiary - Next 27 characters.
     * @return string
     */
    public function getLine28()
    {
        return $this->lines[self::LINE_28] ?? null;
    }

    /**
     * Advice for the Beneficiary - Last 5 characters.
     * @return string
     */
    public function getLine29()
    {
        return $this->lines[self::LINE_29] ?? null;
    }

    /**
     * Debit / Credit Bank Code.
     * @return string
     */
    public function getLine30()
    {
        return $this->lines[self::LINE_30] ?? null;
    }

    /**
     * Debit / Credit Account of Beneficiary.
     * @return string
     */
    public function getLine31()
    {
        return $this->lines[self::LINE_31] ?? null;
    }

    /**
     * Name of Beneficiary 1.
     * @return string
     */
    public function getLine32()
    {
        return $this->lines[self::LINE_32] ?? null;
    }

    /**
     * Name of Beneficiary 2.
     * @return string
     */
    public function getLine33()
    {
        return $this->lines[self::LINE_33] ?? null;
    }

    /**
     * IBAN.
     * @return string
     */
    public function getLine38()
    {
        return $this->lines[self::LINE_38] ?? null;
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
        // Must have an idCode. With specified type.
        $metadata->addPropertyConstraints('idCode', [
            new NotBlank(),
            new Type('string'),
            new Length(['min' => 3, 'max' => 3]),
            new Regex(['pattern' => '/^[a-zA-Z0-9]+$/']),
        ]);
    }
}
