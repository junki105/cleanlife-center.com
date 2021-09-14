<?php # -*- coding: utf-8 -*-

namespace Inpsyde\BackWPup\Pro\License;

use stdClass;

trait RequestHandler
{
    /**
     * It performs an http get request and returns the response body,
     * in case of error, it returns an error object with error message and code.
     *
     * @param string $url
     * @param array $args
     * @return stdClass
     */
    public function doRequest($url, $args)
    {
        $url = add_query_arg($args, $url);
        $response = wp_safe_remote_get($url);

        if (is_wp_error($response)) {
            $errorObject = new stdClass();
            $errorObject->error = $response->get_error_message();
            $errorObject->code = $response->get_error_code();

            return $errorObject;
        }

        return json_decode($response['body']);
    }
}
