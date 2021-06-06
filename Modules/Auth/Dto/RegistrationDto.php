<?php


namespace Modules\Auth\Dto;

use App\Dto\Dto;

class RegistrationDto extends Dto
{
    public $login;
    public $email;
    public $password;
}
