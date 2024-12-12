<?php

namespace EbicsApi\tests\Unit;

use EbicsApi\MT942\MT942Normalizer;
use EbicsApi\MT942\MT942Validator;
use PHPUnit\Framework\TestCase;

/**
 * Class MT942Test.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
final class MT942Test extends TestCase
{

    var $dir = __DIR__ . '/../_data';

    /**
     * @covers
     */
    public function testTransactionListNormalization()
    {
        $str = file_get_contents($this->dir . '/transactions.mt942');
        $normalizer = new MT942Normalizer();
        $transactionList = $normalizer->normalize($str);
        $this->assertNotEmpty($transactionList->count());
    }

    /**
     * @covers
     */
    public function testTransactionNormalization()
    {
        $str = file_get_contents($this->dir . '/transaction.mt942');
        $normalizer = new MT942Normalizer();
        $transaction = $normalizer->normalizeTransaction($str);
        $this->assertNotEmpty($transaction);
    }

    /**
     * @covers
     */
    public function testTransactionListValidation()
    {
        $str = file_get_contents($this->dir . '/transactions.mt942');
        $normalizer = new MT942Normalizer();
        $transactionList = $normalizer->normalize($str);
        $validator = new MT942Validator();
        $violationList = $validator->validateList($transactionList);
        $this->assertEmpty($violationList->count());
    }

    /**
     * @covers
     */
    public function testTransactionValidation()
    {
        $str = file_get_contents($this->dir . '/transaction.mt942');
        $normalizer = new MT942Normalizer();
        $transaction = $normalizer->normalizeTransaction($str);
        $validator = new MT942Validator();
        $violationList = $validator->validate($transaction);
        $this->assertEmpty($violationList->count());
    }
}
