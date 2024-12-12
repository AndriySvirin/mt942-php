<?php

namespace EbicsApi\MT942\contracts;

/**
 * Interface MT942Formatter contains normalization, de-normalization constants.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
interface MT942FormatterInterface
{

    /**
     * Transaction codes.
     */
    const TRANSACTION_CODE_TRN_REF_NR = '20';
    const TRANSACTION_CODE_ACCOUNT_ID = '25';
    const TRANSACTION_CODE_STATEMENT_NR = '28C';
    const TRANSACTION_CODE_FLOOR_LIMIT_INDICATOR = '34F';
    const TRANSACTION_CODE_DATETIME_INDICATOR = '13';
    const TRANSACTION_CODE_STATEMENT_LINE = '61';
    const TRANSACTION_CODE_STATEMENT_INFORMATION = '86';
    const TRANSACTION_CODE_SUMMARY_DEBIT = '90D';
    const TRANSACTION_CODE_SUMMARY_CREDIT = '90C';

    /**
     * Default delimiter.
     */
    const DEFAULT_DELIMITER = PHP_EOL . '-' . PHP_EOL;

    /**
     * Specify manual delimiter.
     *
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): void;
}
