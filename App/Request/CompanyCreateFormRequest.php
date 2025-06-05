<?php

namespace App\Request;

use Core\Request\FormRequest;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;

class CompanyCreateFormRequest extends FormRequest
{
    protected function execute(): bool
    {
        $validate = new Validator();

        $validate->addRule(
            new Key('name', Validator::notEmpty()->setTemplate('Field required')),
        );

        $validate->addRule(
            new Key('cnpj', new AllOf(
                Validator::cnpj()->setTemplate('Field must have a valid cnpj'),
                Validator::notEmpty()->setTemplate('Field required'),
            )),
        );

        return $this->isValidated($validate);
    }
}
