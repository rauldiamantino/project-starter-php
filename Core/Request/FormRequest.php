<?php

namespace Core\Request;

use LogicException;
use Core\Library\Request;
use Core\Library\Session;
use Core\Library\Sanitize;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

abstract class FormRequest
{
    protected Request $request;
    protected Sanitize $validatedData;

    public function isValidated(Validator $validate): bool
    {
        try {
            $validate->assert($this->request->post);
            $this->validatedData = $this->request->getRequest('post');
            return true;
        } catch (NestedValidationException $e) {
            Session::flashes($e->getMessages());

            return false;
        }
    }

    public function setRequest(Request $request): FormRequest
    {
        $this->request = $request;

        return $this;
    }

    public static function validate(Request $request): ?FormRequest
    {
        $instance = new static();
        $instance->setRequest($request);

        if ($instance->execute()) {
            return $instance;
        }

        return null;
    }

    public function validated(): Sanitize
    {
        if (!isset($this->validatedData)) {
            throw new LogicException('validated() called before successful validation or data was not set.');
        }

        return $this->validatedData;
    }

    abstract protected function execute();
}
