<?php

namespace EbicsApi\MT942\models;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Statement Number specifies transaction operation numeration.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class StatementNumber
{

    /**
     * Statement Number.
     * The statement number should be reset to 1 in beginning of every day.
     * @var string
     */
    private $statementNr;

    /**
     * Sequence Number.
     * The sequence number always starts with 001. When several messages are sent to convey information about
     * a single statement, the first message must contain '/001' in Sequence Number.
     * One SWIFT message may contain up to 2000 characters.
     * The sequence number must be incremented by one for each additional message.
     * @var int|null
     */
    private $sequenceNr;

    /**
     * @return string
     */
    public function getStatementNr(): string
    {
        return $this->statementNr;
    }

    /**
     * @param string $value
     */
    public function setStatementNr(string $value): void
    {
        $this->statementNr = $value;
    }

    /**
     * @return int|null
     */
    public function getSequenceNr()
    {
        return $this->sequenceNr;
    }

    /**
     * @param int|null $value
     */
    public function setSequenceNr(int $value = null): void
    {
        $this->sequenceNr = $value;
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
        // Must have a statementNr with specified length and pattern.
        $metadata->addPropertyConstraints('statementNr', [
            new NotBlank(),
            new Length(['min' => 5, 'max' => 5]),
            new Regex(['pattern' => '/^\d+$/']),
        ]);

        // Can have a sequenceNr with specified length and pattern.
        $metadata->addPropertyConstraints('statementNr', [
            new Length(['min' => 5, 'max' => 5]),
            new Regex(['pattern' => '/^\d+$/']),
        ]);
    }
}
