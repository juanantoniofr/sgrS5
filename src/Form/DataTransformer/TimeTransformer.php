<?php
// src/Form/DataTransformer/DateTimeTransformer.php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TimeTransformer implements DataTransformerInterface
{
    
    /**
     * Transforms an object (Time) to a string.
     *
     * @param  Time|null $datetime
     * @return string
     */
    public function transform($time)
    {
        if (null === $time) {
            return '';
        }

        return $time->format('H:i');
    }

    /**
     * Transforms a string to an object (Time).
     *
     * @param  string $time
     * @return Time|null
     */
    public function reverseTransform($time)
    {
        
        if (!$time) {
            return;
        }

        return date_create_from_format('H:i', $time, new \DateTimeZone('Europe/Madrid'));
    }
}