<?php
// src/Form/DataTransformer/DateTimeTransformer.php
namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformer implements DataTransformerInterface
{
    
    /**
     * Transforms an object (DateTime) to a string.
     *
     * @param  DateTime|null $datetime
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }

        return $datetime->format('d/m/Y');
    }

    /**
     * Transforms a string to an object (DateTime).
     *
     * @param  string $datetime
     * @return DateTime|null
     */
    public function reverseTransform($datetime)
    {
        
        //if (!$datetime) {
        if (!date_create_from_format('d/m/Y', $datetime, new \DateTimeZone('Europe/Madrid'))){
              throw new TransformationFailedException();
            //return;
        }

        return date_create_from_format('d/m/Y', $datetime, new \DateTimeZone('Europe/Madrid'));
    }
}