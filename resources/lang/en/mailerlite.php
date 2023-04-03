<?php

return [

    'messages' => [
        '200' => 'The request was accepted.',
        '201' => 'Resource was created.',
        '202' => 'The request was accepted and further actions are taken in the background.',
        '204' => 'The request was accepted and there is nothing to return.',
        '400' => 'There was an error when processing your request. Please adjust your request based on the endpoint requirements and try again.',
        '401' => 'The provided API token is invalid.',
        '403' => 'The action is denied for that account or a particular API token.',
        '404' => 'The requested resource does not exist on the system.',
        '405' => 'HTTP method is not supported by the requested endpoint.',
        '408' => 'There is an error on our system. Please contact support',
        '422' => 'There was a validation error found when processing the request. Please adjust it based on the endpoint requirements and try again.',
        '429' => 'There were too many requests made to the API.',
        '500' => 'There was an error on our system. Please contact support',
        '502' => 'There was an error on our system. Please contact support',
        '503' => 'There was an error on our system. Please contact support',
        '504' => 'There was an error on our system. Please contact support',
    ],

    'no_subscribers_found' => 'No subscribers found.'

];
