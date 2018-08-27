<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\EmailValidator as EmailValidator;

class EmailValidatorTest extends TestCase
{
    
    /**
     * Check corect e-mails
     *
     * @return void
     */
    public function testCorrect()
    {
        $this->assertTrue(EmailValidator::valid('lechbaczynski@arystoteles.pl'));
    }
    
    
    /**
     * Check synctactically incorect e-mails
     *
     * @return void
     */
    public function testIncorrect()
    {
        $this->assertFalse(EmailValidator::valid('0'));
        $this->assertFalse(EmailValidator::valid(0));
        $this->assertFalse(EmailValidator::valid('abc'));
        $this->assertFalse(EmailValidator::valid('examplegmailcom'));
        $this->assertFalse(EmailValidator::valid('abc.net'));
        $this->assertFalse(EmailValidator::valid('examplegmail.com'));
        $this->assertFalse(EmailValidator::valid('abc@net@asdf'));
        $this->assertFalse(EmailValidator::valid('@examplegmailcom'));
    }
    
    /**
     * Check synctactically corect e-mails with bad domain
     *
     * @return void
     */
    public function testBadDomains()
    {
        $this->assertFalse(EmailValidator::valid('example@examplegmailcom'));
        $this->assertFalse(EmailValidator::valid('example@examp..legmailcom'));
        $this->assertFalse(EmailValidator::valid('dave@gmoooooooil.com'));
    }
}
