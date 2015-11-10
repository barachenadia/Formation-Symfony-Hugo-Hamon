<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringPhoneNumberToArrayTransformer implements DataTransformerInterface
{
    private $firstName;
    private $secondName;

    public function __construct($firstName, $secondName)
    {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
    }

    public function transform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException();
        }

        $values = explode('/', ltrim($value, '+'));
        if (2 !== count($values)) {
            throw new TransformationFailedException();
        }

        // Use array_combine() instead???
        return [
            $this->firstName = $values[0],
            $this->secondName = $values[1],
        ];
    }

    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException();
        }

        if (2 !== count($value)) {
            throw new TransformationFailedException();
        }

        if (!isset($value[$this->firstName]) || !isset($value[$this->secondName])) {
            throw new TransformationFailedException();
        }
        
        return sprintf('+%s/%s', $value[$this->firstName], $value[$this->secondName]);
    }
}
