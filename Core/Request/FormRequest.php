<?php

namespace Core\Request;

use Core\Library\Request;
use Core\Library\Session;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

abstract class FormRequest
{
    protected Request $request;

    public function is_validated(Validator $validate): bool
    {
        try {
            $validate->assert($this->request->post);

            return true;
        } catch (NestedValidationException $e) {
            Session::flashes($e->getMessages());

            return false;
        }
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public static function validate(Request $request)
    {
        return (new static())->setRequest($request)->execute();
    }

    abstract protected function execute();
}
