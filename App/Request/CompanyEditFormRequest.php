<?php

namespace App\Request;

use Core\Request\FormRequest;
use Respect\Validation\Rules\Key;
use Respect\Validation\Validator;
use Respect\Validation\Rules\AllOf;

class CompanyEditFormRequest extends FormRequest
{
    protected function execute(): bool
    {
        $validate = new Validator();

        $validate->addRule(
            new Key('is_active', new AllOf(
                Validator::in([0, 1])->setTemplate('The selected status is invalid'),
            )),
        );

        $validate->addRule(
            new Key('name', Validator::notEmpty()->setTemplate('Field required')),
        );

        $validate->addRule(
            new Key('slug', Validator::not(Validator::alwaysValid())->setTemplate('Editing slug is not allowed'), false)
        );


        $validate->addRule(
            new Key('cnpj', new AllOf(
                Validator::cnpj()->setTemplate('Field must have a valid cnpj'),
                Validator::notEmpty()->setTemplate('Field required'),
            ))
        );

        return $this->isValidated($validate);
    }
}
