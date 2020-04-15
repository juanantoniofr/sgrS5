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
        //dump($datetime);
        //dump((new \DateTime())->format('d/m/Y'));
        //exit;
        
        if (null === $datetime) {
            return (new \DateTime())->format('d/m/Y');
        }
        //dump(date_create_from_format('d/m/Y', $datetime, new \DateTimeZone('Europe/Madrid')));
        //return date_create_from_format('d/m/Y', $datetime, new \DateTimeZone('Europe/Madrid'));
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
        //dump(!$datetime);
        //dump($datetime);
        //dump(date_create_from_format('d/m/Y H:i', $datetime . '00:00', new \DateTimeZone('Europe/Madrid')));
        //exit;
        if (!$datetime || !date_create_from_format('d/m/Y H:i', $datetime . '00:00', new \DateTimeZone('Europe/Madrid'))) {
              throw new TransformationFailedException('');
        }
        return date_create_from_format('d/m/Y H:i', $datetime . '00:00', new \DateTimeZone('Europe/Madrid'));
    }
}