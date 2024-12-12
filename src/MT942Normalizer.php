<?php

namespace EbicsApi\MT942;

use EbicsApi\MT942\models\AccountIdentification;
use EbicsApi\MT942\models\FloorLimitIndicator;
use EbicsApi\MT942\models\Statement;
use EbicsApi\MT942\models\StatementInformation;
use EbicsApi\MT942\models\StatementLine;
use EbicsApi\MT942\models\StatementNumber;
use EbicsApi\MT942\models\Summary;
use EbicsApi\MT942\models\Transaction;
use DateTime;
use RuntimeException;

/**
 * Main MT942 parser class.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
final class MT942Normalizer extends MT942Formatter
{

    /**
     * Normalize string with list of Transactions from string.
     *
     * @param string $str Encoded entity.
     *
     * @return TransactionList
     */
    public function normalize(string $str): TransactionList
    {
        if (empty($this->delimiter)) {
            throw new RuntimeException('Separator is empty.');
        }
        $records = explode($this->delimiter, $str);
        if (empty($records)) {
            throw new RuntimeException('No records found.');
        }
        $result = new TransactionList();
        foreach ($records as $record) {
            $result->add($this->normalizeTransaction($record));
        }
        return $result;
    }

    /**
     * Normalize Transaction item.
     *
     * @param string $str Contains encoded information about transactions.
     *
     * @return Transaction
     */
    public function normalizeTransaction(string $str): Transaction
    {
        // Extract from record pairs code and message, all other keys are overhead.
        preg_match_all(
            '/:(?!\n)(?<code>[0-9A-Z]*):(?<message>((?!\n:).)*)/s',
            $str,
            $transactionDetails,
            PREG_SET_ORDER
        );
        $transaction = new Transaction();
        foreach ($transactionDetails as $transactionDetail) {
            switch ($transactionDetail['code']) {
                case self::TRANSACTION_CODE_TRN_REF_NR:
                    $transaction->setTrnRefNr($transactionDetail['message']);
                    break;
                case self::TRANSACTION_CODE_ACCOUNT_ID:
                    $transaction->setAccountIdentification(
                        $this->normalizeAccountIdentification($transactionDetail['message'])
                    );
                    break;
                case self::TRANSACTION_CODE_STATEMENT_NR:
                    $transaction->setStatementNumber($this->normalizeStatementNumber($transactionDetail['message']));
                    break;
                case self::TRANSACTION_CODE_FLOOR_LIMIT_INDICATOR:
                    // If floor limit indicator occur second time, then this is credit type.
                    if (null === $transaction->getFloorLimitIndicator()) {
                        $transaction->setFloorLimitIndicator(
                            $this->normalizeFloorLimitIndicator($transactionDetail['message'])
                        );
                    } else {
                        $transaction->setCreditFloorLimitIndicator(
                            $this->normalizeFloorLimitIndicator($transactionDetail['message'])
                        );
                    }
                    break;
                case self::TRANSACTION_CODE_DATETIME_INDICATOR:
                    $transaction->setDatetimeIndicator(
                        $this->normalizeDatetimeIndicator($transactionDetail['message'])
                    );
                    break;
                case self::TRANSACTION_CODE_STATEMENT_LINE:
                    $statement = new Statement();
                    $transaction->addStatement($statement);
                    $statement->setLine($this->normalizeStatementLine($transactionDetail['message']));
                    break;
                case self::TRANSACTION_CODE_STATEMENT_INFORMATION:
                    // Add statement information to current statement declared in row before.
                    /* @var $statement Statement */
                    if (isset($statement)) {
                        $statement->setInformation($this->normalizeStatementInformation($transactionDetail['message']));
                    }
                    break;
                case self::TRANSACTION_CODE_SUMMARY_DEBIT:
                    $transaction->setSummaryDebit($this->normalizeSummary($transactionDetail['message']));
                    break;
                case self::TRANSACTION_CODE_SUMMARY_CREDIT:
                    $transaction->setSummaryCredit($this->normalizeSummary($transactionDetail['message']));
                    break;
            }
        }
        return $transaction;
    }

    /**
     * Normalize transaction AccountIdentification from string.
     *
     * @param string $str Encoded entity.
     *
     * @return AccountIdentification
     */
    private function normalizeAccountIdentification(string $str): AccountIdentification
    {
        preg_match_all(
            '/(?<bic>[0-9A-Z]*)\/(?<acc_nr>[0-9A-Z]*)/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new AccountIdentification();
        if (!empty($details[0]['bic']) && !empty($details[0]['acc_nr'])) {
            $result->setTypeBAN();
            $result->setBIC($details[0]['bic']);
            $result->setAccNr($details[0]['acc_nr']);
        } else {
            preg_match_all(
                '/(?<country_code>[0-9A-Z]{2})(?<control_code>[0-9A-Z]{2})(?<bban>[0-9A-Z]*)/s',
                $str,
                $details,
                PREG_SET_ORDER
            );
            $result->setTypeIBAN();
            $result->setIBANCountryCode($details[0]['country_code']);
            $result->setIBANControlCode($details[0]['control_code']);
            $result->setIBANBBAN($details[0]['bban']);
        }
        return $result;
    }

    /**
     * Normalize transaction StatementNumber from string.
     *
     * @param string $str Encoded entity.
     *
     * @return StatementNumber
     */
    private function normalizeStatementNumber(string $str): StatementNumber
    {
        preg_match_all(
            '/(?<statement_nr>[0-9A-Z]{1,5})(\/?(?<sequence_nr>[0-9A-Z]{1,5})?)/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new StatementNumber();
        $result->setStatementNr($details[0]['statement_nr']);
        $result->setSequenceNr(!empty($details[0]['sequence_nr']) ? $details[0]['sequence_nr'] : null);
        return $result;
    }

    /**
     * Normalize transaction FloorLimitIndicator from string.
     *
     * @param string $str Encoded entity.
     *
     * @return FloorLimitIndicator
     */
    private function normalizeFloorLimitIndicator(string $str): FloorLimitIndicator
    {
        preg_match_all(
            '/(?<currency>[A-Z]{3})(?<mark>[A-Z]{0,1})(?<amount>[0-9,]*)/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new FloorLimitIndicator();
        $result->setMark(!empty($details[0]['mark']) ? $details[0]['mark'] : null);
        $money = $result->getMoney();
        $money->setCurrency($details[0]['currency']);
        $money->setAmount($this->normalizeAmount($details[0]['amount']));
        return $result;
    }

    /**
     * Normalize transaction DateTime from string.
     *
     * @param string $str Encoded entity.
     *
     * @return DateTime
     */
    private function normalizeDatetimeIndicator(string $str): DateTime
    {
        if (!($result = DateTime::createFromFormat('ymdHi', $str))) {
            throw new RuntimeException(' Incorrect date.');
        }
        return $result;
    }

    /**
     * Normalize transaction statement line from string.
     *
     * @param string $str Encoded entity.
     *
     * @return StatementLine
     */
    private function normalizeStatementLine(string $str): StatementLine
    {
        preg_match_all(
            '/(?<value_date>[0-9]{6})(?<entry_date>[0-9]{0,4})(?<mark>[A-Z]{1})' .
            '(?<amount>[0-9,]{1,15})(?<transaction_type_id_code>[A-Z0-9]{4})(?<customer_ref>[a-zA-Z0-9]{1,16})/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new StatementLine();
        if (($valueDate = DateTime::createFromFormat('ymd', $details[0]['value_date']))) {
            $result->setValueDate($valueDate);
        }
        $result->setEntryDate(!empty($details[0]['entry_date']) ? $details[0]['entry_date'] : null);
        $result->setMark($details[0]['mark']);
        $result->setAmount($this->normalizeAmount($details[0]['amount']));
        $result->setTransactionTypeIdCode($details[0]['transaction_type_id_code']);
        $result->setCustomerRef($details[0]['customer_ref']);
        return $result;
    }

    /**
     * Normalize transaction statement line information from string.
     *
     * @param string $str Encoded entity.
     *
     * @return StatementInformation
     */
    private function normalizeStatementInformation(string $str): StatementInformation
    {
        preg_match_all(
            '/(?<id_code>[^?]{3})?(\?(?<nr>\d\d)(?<line>[^\?]*))/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new StatementInformation();
        $result->setIdCode($details[0]['id_code']);
        foreach ($details as $detail) {
            $result->addLine($detail['nr'], rtrim($detail['line']));
        }
        return $result;
    }

    /**
     * Normalize transaction Debit or Credit Summary from string.
     *
     * @param string $str Encoded entity.
     *
     * @return Summary
     */
    private function normalizeSummary(string $str): Summary
    {
        preg_match_all(
            '/(?<entries_nr>[0-9]{0,5})(?<currency>[A-Z]{3})(?<amount>[0-9,]*)/s',
            $str,
            $details,
            PREG_SET_ORDER
        );
        $result = new Summary();
        $result->setEntriesNr(!empty($details[0]['entries_nr']) ? $details[0]['entries_nr'] : null);
        $money = $result->getMoney();
        $money->setCurrency($details[0]['currency']);
        $money->setAmount($this->normalizeAmount($details[0]['amount']));
        return $result;
    }

    /**
     * Parse amount value as string. Replace comma by dot in order to get correct float value.
     *
     * @param string $amount
     *
     * @return float
     */
    private function normalizeAmount(string $amount): float
    {
        return (float)str_replace(',', '.', $amount);
    }
}
