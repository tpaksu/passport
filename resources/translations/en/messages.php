<?php

return [
    'unsupported_grant_type' => 'The authorization grant type is not supported by the authorization server.',
    'unsupported_grant_type_hint' => 'Check the `grant_type` parameter',
    'invalid_request' => 'The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.',
    'invalid_request_hint' => 'Check the `%s` parameter',
    'invalid_client' => 'Client authentication failed',
    'invalid_scope' => 'The requested scope is invalid, unknown, or malformed',
    'invalid_credentials' => 'The user credentials were incorrect.',
    'server_error' => 'The authorization server encountered an unexpected condition which prevented it from fulfilling the request: :hint',
    'invalid_request' => 'The refresh token is invalid.',
    'access_denied' => 'The resource owner or authorization server denied the request.',
    'invalid_grant' => 'The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.',
    'access_token_duplicate' => 'Could not create unique access token identifier',
];
