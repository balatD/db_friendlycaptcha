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

namespace BalatD\FriendlyCaptcha\ViewHelpers\Form;

use BalatD\FriendlyCaptcha\Services\FriendlyCaptchaService;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;

class FriendlycaptchaViewHelper extends AbstractFormFieldViewHelper
{
    protected FriendlyCaptchaService $captchaService;

    public function __construct(FriendlyCaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
        parent::__construct();
    }

    public function render(): string
    {
        $name = $this->getName();
        $this->registerFieldNameForFormTokenGeneration($name);

        $container = $this->templateVariableContainer;
        $container->add('configuration', $this->captchaService->getConfiguration());
        $container->add('name', $name);

        $content = $this->renderChildren();

        $container->remove('name');
        $container->remove('configuration');

        return $content;
    }
}
