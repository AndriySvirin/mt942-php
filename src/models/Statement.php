<?php

namespace EbicsApi\MT942\models;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Statement specifies information about Transaction operation.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class Statement
{

    /**
     * @var StatementLine
     */
    private $line;

    /**
     * @var StatementInformation
     */
    private $information;

    /**
     * @return StatementLine
     */
    public function getLine(): StatementLine
    {
        return $this->line;
    }

    /**
     * @param StatementLine $value
     */
    public function setLine(StatementLine $value): void
    {
        $this->line = $value;
    }

    /**
     * @return null|StatementInformation
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * @param StatementInformation $value
     */
    public function setInformation(StatementInformation $value): void
    {
        $this->information = $value;
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
        // Must have a valid line.
        $metadata->addPropertyConstraints('line', [
            new Valid(),
            new NotBlank(),
            new Type('object'),
        ]);
        // Must have a valid information.
        $metadata->addPropertyConstraints('information', [
            new Valid(),
            new NotBlank(),
            new Type('object'),
        ]);
    }
}
