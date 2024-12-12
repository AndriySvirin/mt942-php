<?php

namespace EbicsApi\MT942;

use EbicsApi\MT942\contracts\MT942FormatterInterface;

/**
 * Main MT942 basic formatter settings.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
abstract class MT942Formatter implements MT942FormatterInterface
{

    /**
     * Delimiter that slice string on transactions pieces.
     * @var string
     */
    protected $delimiter;

    public function __construct(string $delimiter = null)
    {
        $this->delimiter = $delimiter ?? self::DEFAULT_DELIMITER;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }
}
