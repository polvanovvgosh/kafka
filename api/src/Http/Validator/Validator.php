<?php

declare(strict_types=1);


namespace Api\Http\Validator;


use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     *
     * @return Errors|null
     */
    public function validate($object): ?Errors
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            return new Errors($violations);
        }
        return null;
    }
}