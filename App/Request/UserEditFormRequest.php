<?php

namespace App\Request;

use Core\Request\FormRequest;
use Respect\Validation\Validator;
use Respect\Validation\Rules\Key;
use Respect\Validation\Rules\AllOf;

class UserEditFormRequest extends FormRequest
{
    protected function execute(): bool
    {
        $validate = new Validator();

        $validate->addRule(
            new Key('name', Validator::notEmpty()->setTemplate('The name field is required.'), true),
        );

        $validate->addRule(
            new Key('email', new AllOf(
                Validator::email()->setTemplate('The email field must be a valid email address.'),
                Validator::notEmpty()->setTemplate('The email field is required.'),
            ), true)
        );

        $validate->addRule(
            new Key('level', new AllOf(
                Validator::in([1, 2, 3])->setTemplate('The selected level is invalid.'),
                Validator::notEmpty()->setTemplate('The level field is required.'),
            ), true)
        );

        return $this->isValidated($validate);
    }
}
