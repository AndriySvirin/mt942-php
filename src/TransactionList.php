<?php

namespace EbicsApi\MT942;

use EbicsApi\MT942\models\Transaction;
use ArrayIterator;
use IteratorAggregate;

/**
 * Transaction list handler.
 * Could be extended for additional search methods in transactions.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class TransactionList implements IteratorAggregate
{

    /**
     * @var Transaction[]
     */
    private $transactions = [];

    /**
     * {@inheritDoc}
     * @return Transaction[]|ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->transactions);
    }

    /**
     * Get amount of transactions.
     * @return int
     */
    public function count(): int
    {
        return count($this->transactions);
    }

    /**
     * Add one transaction to list.
     *
     * @param Transaction $transaction
     */
    public function add(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }
}
