# MT942-PHP
This tools convert MT942 formatted text to PHP objects. PHP library for parse MT942 format that uses Swift.  
Banks uses MT942 format for payments data transition.  
More details about MT942 format you can find in Internet.

Helper for [EBICS Client PHP](https://github.com/ebics-api/ebics-client-php)

### Installation
```bash
$ composer require ebics-api/mt942-php
```

### License
ebics-api/mt942-php is licensed under the MIT License, see the LICENSE file for details

### Example
Normalize:
```php
 $str = file_get_contents('path_to_file.mt942');
 $normalizer = new EbicsApi\MT942\MT942Normalizer();
 $transactionList = $normalizer->normalize($str);
```
Validate:
```php      
 $validator = new MT942Validator();
 $violationList = $validator->validateList($transactionList);
```
