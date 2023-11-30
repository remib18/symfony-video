<?php

namespace App\Security;

use AllowDynamicProperties;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

#[AllowDynamicProperties] class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse|Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}