<?php
/**
 * Created by PhpStorm.
 * User: duviel
 * Date: 14/08/16
 * Time: 05:53 PM
 */

namespace Duvg\ApiBundle\Helpers;


class Helper
{
    function serializer($data)
    {
        $serializerjms = $this->get('jms_serializer');
        return $serializerjms->serialize($data, 'json');
    }

}