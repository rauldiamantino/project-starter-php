<?php

namespace App\Request;

use Core\Request\FormRequest;

use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;
use Respect\Validation\Rules\AllOf;

class UserCreateFormRequest extends FormRequest
{
    protected function execute(): bool
    {
        $validate = new Validator();

        $validate->addRule(
            new Key('name', Validator::notEmpty()->setTemplate('Field required')),
        );

        $validate->addRule(
            new Key('email', new AllOf(
                Validator::email()->setTemplate('Field must have a valid email'),
                Validator::notEmpty()->setTemplate('Field required'),
            )),
        );

        $validate->addRule(
            new Key('password', new AllOf(
                Validator::notEmpty()->setTemplate('Field required'),
                Validator::length(5, null)->setTemplate('Field requires at least 5 characters'),
            )),
        );

        $validate->addRule(
            new Key('company_id', Validator::notEmpty()->setTemplate('Field required')),
        );

        $validate->addRule(
            new Key('level', Validator::notEmpty()->setTemplate('Field required')),
        );

        return $this->isValidated($validate);
    }
}
