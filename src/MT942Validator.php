<?php

namespace EbicsApi\MT942;

use EbicsApi\MT942\models\Transaction;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validator class for @see Transaction and related models.
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrew Svirin
 */
class MT942Validator
{

    /**
     * Validate list of Transactions model by constraints.
     *
     * @param TransactionList $transactionList Models for validation.
     * @param ValidatorInterface|null $validator Extendable validator. By default uses default validator.
     *
     * @return ConstraintViolationList
     */
    public function validateList(TransactionList $transactionList, ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = $this->getValidator();
        }
        $result = new ConstraintViolationList();
        foreach ($transactionList->getIterator() as $transaction) {
            $result->addAll($this->validate($transaction, $validator));
        }
        return $result;
    }

    /**
     * Validate Transaction model by constraints.
     *
     * @param Transaction $transaction Model for validation.
     * @param ValidatorInterface $validator Extendable validator. By default uses default validator.
     *
     * @return ConstraintViolationListInterface
     */
    public function validate(Transaction $transaction, ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = $this->getValidator();
        }
        $result = $validator->validate($transaction);
        return $result;
    }

    /**
     * Get default Validator.
     *
     * @param array|null $validationMethods Can be extended by additional validation methods.
     *
     * @return ValidatorInterface
     */
    public function getValidator(array $validationMethods = null)
    {
        if (null === $validationMethods) {
            $validationMethods = ['loadValidatorMetadata'];
        }
        $result = Validation::createValidatorBuilder()
            ->addMethodMappings($validationMethods)
            ->getValidator();
        return $result;
    }
}
