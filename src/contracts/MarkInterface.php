<?php

namespace EbicsApi\MT942\contracts;

/**
 * Interface MarkInterface contains debit/credit mark constants.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
interface MarkInterface
{

    /**
     * Marks.
     */
    const MARK_DEBIT = 'D';
    const MARK_CREDIT = 'C';
}
