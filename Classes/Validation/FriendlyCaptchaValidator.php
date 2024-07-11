<?php

declare(strict_types=1);

/*
 * This file is developed by balatD.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace BalatD\FriendlyCaptcha\Validation;

use BalatD\FriendlyCaptcha\Services\FriendlyCaptchaService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class FriendlyCaptchaValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    public function __construct(protected FriendlyCaptchaService $captchaService)
    {
    }

    /**
     * Checks if the given value is valid according to the validator, and returns
     * the error messages object which occurred.
     */
    public function validate(mixed $value): Result
    {
        $value = trim($this->getRequest()->getParsedBody()['frc-captcha-solution'] ?? '');
        return parent::validate($value);
    }

    /**
     * Validate the captcha value from the request and add an error if not valid
     */
    public function isValid(mixed $value): void
    {
        $status = $this->captchaService->validateFriendlyCaptcha($value);
        if ($status['error'] !== '') {
            $errorText = $this->translateErrorMessage('error_friendlycaptcha_' . $status['error'], 'friendlycaptcha');

            if (empty($errorText)) {
                $errorText = htmlspecialchars($status['error']);
            }

            $this->addError($errorText, 1519982126);
        }
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
