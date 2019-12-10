<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 11/12/2019
 * Time: 10:04
 */

namespace App\Util;

/**
 * Class ErrorMessageHelper
 * @package App\Util
 */
class ErrorMessageHelper
{
    /**
     * getInvalidInputsMessage
     * @param array $data
     * @return array
     */
    public static function getInvalidInputsMessage(array $data, bool $translate = false): array
    {
        //      error1 = return.error.1 = Your request failed because your inputs were invalid.
        $data['errors'][] = ['class' => 'error', 'message' => ($translate ? TranslationsHelper::translate('return.error.1', [], 'messages') : ['return.error.1', [], 'messages'])];
        $data['status'] = 'error';
        return $data;
    }
}