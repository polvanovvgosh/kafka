<?php

declare(strict_types=1);


namespace Api\Http\Validator;


use Symfony\Component\Validator\ConstraintViolationListInterface;

class Errors
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $errors = [];

        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}