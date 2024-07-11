<?php

declare(strict_types=1);

/*
 * This file is developed by evoWeb.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace BalatD\FriendlyCaptcha\Adapter;

use BalatD\FriendlyCaptcha\Services\FriendlyCaptchaService;
use Evoweb\SfRegister\Services\Captcha\AbstractAdapter;
use Evoweb\SfRegister\Services\Session;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class SfRegisterAdapter extends AbstractAdapter
{
    public function __construct(protected FriendlyCaptchaService $friendlyCaptcha, protected Session $session)
    {
    }

    /**
     * Rendering the output of the captcha
     *
     * @return string
     */
    public function render(): string
    {
        $this->session->remove('captchaWasValid');

        if ($this->friendlyCaptcha !== null) {
            $output = $this->friendlyCaptcha->getFriendlyCaptcha();
        } else {
            $output = LocalizationUtility::translate(
                'error_captcha.notinstalled',
                'friendlycaptcha'
            );
        }

        return $output;
    }

    /**
     * Validate the captcha value from the request and output an error if not valid
     *
     * @param string $value
     *
     * @return bool
     */
    public function isValid(string $value): bool
    {
        $validCaptcha = true;

        if ($this->session->get('captchaWasValid') !== true) {
            $status = $this->friendlyCaptcha->validateFriendlyCaptcha($value);

            if ($status['error'] !== '') {
                $validCaptcha = false;
                $this->addError(
                    LocalizationUtility::translate(
                        'error_friendlycaptcha_' . $status['error'],
                        'friendlycaptcha'
                    ),
                    1307421961
                );
            }

            $this->session->set('captchaWasValid', $validCaptcha);
        }

        return $validCaptcha;
    }
}
