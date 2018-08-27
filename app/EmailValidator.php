<?php
namespace App;

use EmailValidation;


/**
 * Validates e-mail.
 * Our encapsulation of vendor e-mail validators and business logic
 */
class EmailValidator
{
  
    /**
     * Validates e-mail.
     * Our encapsulation of vendor e-mail validators and business logic
     * 
     *  @param string $email Email to be checked
        *
        * @return bool Returns it it is valid
    */
    public static function valid($email) {
    
        $validator = EmailValidation\EmailValidatorFactory::create($email);
        $arrayResult = $validator->getValidationResults()->asArray();
        
        
        if ($arrayResult['valid_format'] && 
                $arrayResult['valid_host'] && 
                $arrayResult['valid_mx_records'] ) {
            return true;
        };
        return false;
        
        
    }
    
    
    
    
    
}
