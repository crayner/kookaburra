<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 11/12/2019
 * Time: 10:04
 */

namespace App\Util;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

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
    /**
     * getInvalidInputsMessage
     * @param array $data
     * @return array
     */
    public static function getDatabaseErrorMessage(array $data, bool $translate = false): array
    {
        //      error2 = return.error.2 = Your request failed due to a database error.
        $data['errors'][] = ['class' => 'error', 'message' => ($translate ? TranslationsHelper::translate('return.error.2', [], 'messages') : ['return.error.2', [], 'messages'])];
        $data['status'] = 'error';
        return $data;
    }

    /**
     * getSuccessMessage
     * @param array $data
     * @param bool $translate
     * @return array
     */
    public static function getSuccessMessage(array $data, bool $translate = false): array
    {
        //      success0 = return.success.0 = Your request was completed successfully.
        $data['errors'][] = ['class' => 'success', 'message' => ($translate ? TranslationsHelper::translate('return.success.0', [], 'messages') : ['return.success.0', [], 'messages'])];
        $data['status'] = 'success';
        return $data;
    }

    /**
     * uniqueErrors
     * @param array $data
     * @param bool $translate
     * @return array
     */
    public static function uniqueErrors(array $data, bool $translate = false): array
    {
        $data['errors'] = array_unique(isset($data['errors']) ? $data['errors'] : [], SORT_REGULAR);

        if ($translate){
            foreach($data['errors'] as $q=>$error) {
                if (is_array($error['message']) && count($error['message']) === 3)
                    $data['errors'][$q]['message'] = TranslationsHelper::translate($error['message'][0],$error['message'][1],$error['message'][2]);
                else
                    $data['errors'][$q]['message'] = TranslationsHelper::translate($error['message']);
            }
        }

        $data['errors'] = array_unique($data['errors'], SORT_REGULAR);
        return $data;
    }

    /**
     * convertToFlash
     * @param array $data
     * @param FlashBagInterface $flashBag
     */
    public static function convertToFlash(array $data, FlashBagInterface $flashBag)
    {
        foreach(self::uniqueErrors($data)['errors'] as $error) {
            $flashBag->add($error['class'], $error['message']);
        }
    }

    /**
     *
    $returns['success0'] = __('Your request was completed successfully.');
    $returns['error0'] = __('Your request failed because you do not have access to this action.');
    $returns['error1'] = __('Your request failed because your inputs were invalid.');
    $returns['error2'] = __('Your request failed due to a database error.');
    $returns['error3'] = __('Your request failed because your inputs were invalid.');
    $returns['error4'] = __('Your request failed because your passwords did not match.');
    $returns['error5'] = __('Your request failed because there are no records to show.');
    $returns['error6'] = __('Your request was completed successfully, but there was a problem saving some uploaded files.');
    $returns['error7'] = __('Your request failed because some required values were not unique.');
    $returns['error8'] = __('Your request failed because some values are still in use within the data.');
    $returns['warning0'] = __('Your optional extra data failed to save.');
    $returns['warning1'] = __('Your request was successful, but some data was not properly saved.');
    $returns['warning2'] = __('Your request was successful, but some data was not properly deleted.');

     */
}