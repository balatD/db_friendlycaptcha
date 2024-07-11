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

namespace BalatD\FriendlyCaptcha\Services;

use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\Exception\MissingArrayPathException;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class FriendlyCaptchaService
{
    protected array $configuration = [];

    public function __construct(
        protected ExtensionConfiguration $extensionConfiguration,
        protected ConfigurationManagerInterface $configurationManager,
        protected TypoScriptService $typoScriptService,
        protected ContentObjectRenderer $contentRenderer,
        protected RequestFactory $requestFactory
    ) {
        $this->initialize();
    }

    /**
     * @throws MissingArrayPathException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function initialize(): void
    {
        $configuration = $this->extensionConfiguration->get('friendlycaptcha');

        if (!is_array($configuration)) {
            $configuration = [];
        }

        $typoScriptConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'friendlycaptcha'
        );

        if (!empty($typoScriptConfiguration)) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $configuration,
                $this->typoScriptService->convertPlainArrayToTypoScriptArray($typoScriptConfiguration),
                true,
                false
            );
        }

        if (!is_array($configuration) || empty($configuration)) {
            throw new MissingArrayPathException(
                'Please configure plugin.tx_friendlycaptcha. before rendering the friendlycaptcha',
                1417680292
            );
        }

        $this->configuration = $configuration;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * Build Friendly Captcha Frontend HTML-Code
     */
    public function getFriendlyCaptcha(): string
    {
        return $this->contentRenderer->stdWrap(
            $this->configuration['public_key'] ?? '',
            $this->configuration['public_key.'] ?? []
        );
    }

    /**
     * Validate Friendly Captcha challenge/response
     */
    public function validateFriendlyCaptcha(string $value = ''): array
    {
        $request = [
            'secret' => $this->configuration['private_key'],
            'solution' => trim($value ?? $this->getRequest()->getParsedBody()['frc-captcha-solution'] ?? ''),
            'sitekey' => $this->configuration['public_key'],
        ];

        $result = [
            'verified' => false,
            'error' => ''
        ];
        if (empty($request['solution'])) {
            $result['error'] = 'missing-input-solution';
        } else {
            $response = $this->queryVerificationServer($request);
            if (!$response) {
                $result['error'] = 'validation-server-not-responding';
            }

            if ($response['success']) {
                $result['verified'] = true;
            } else {
                $result['error'] = (string)(
                    is_array($response['error-codes']) ?
                    reset($response['error-codes']) :
                    $response['error-codes']
                );
            }
        }

        return $result;
    }

    /**
     * Query Friendly Captcha server for captcha-verification
     */
    protected function queryVerificationServer(array $data): array
    {
        $verifyServerInfo = @parse_url($this->configuration['verify_server'] ?? '');

        if (empty($verifyServerInfo)) {
            return [
                'success' => false,
                'error-codes' => 'friendlycaptcha-not-reachable'
            ];
        }

        $response = $this->requestFactory->request(
            $this->configuration['verify_server'],
            'POST',
            [RequestOptions::JSON => $data]

        );

        $body = (string)$response->getBody();
        return $body ? json_decode($body, true) : [];
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
