<?php
namespace Dropbox;

/**
 * A minimal wrapper around a cURL handle.
 *
 * @internal
 */
final class Curl
{
    /** @var resource */
    public $handle;

    /** @var string[] */
    private $headers = array();

    /**
     * @param string $url
     */
    function __construct($url)
    {
        // Make sure there aren't any spaces in the URL (i.e. the caller forgot to URL-encode).
        if (strpos($url, ' ') !== false) {
            throw new \InvalidArgumentException("Found space in \$url; it should be encoded");
        }

        $this->handle = curl_init($url);

        // NOTE: Though we turn on SSL settings the best we can, PHP doesn't always obey these
        // settings.  Check out the "ssl-check.php" example to see how well your PHP
        // installation handles these SSL settings.

        // Force SSL and use our own certificate list.
        $this->set(CURLOPT_SSL_VERIFYPEER, true);   // Enforce certificate validation
        $this->set(CURLOPT_SSL_VERIFYHOST, 2);      // Enforce hostname validation
        $this->set(CURLOPT_SSLVERSION, 3);          // Enforce SSL v3.

        // Only allow ciphersuites supported by Dropbox
        $this->set(CURLOPT_SSL_CIPHER_LIST,
            'ECDHE-RSA-AES256-GCM-SHA384:'.
            'ECDHE-RSA-AES128-GCM-SHA256:'.
            'ECDHE-RSA-AES256-SHA384:'.
            'ECDHE-RSA-AES128-SHA256:'.
            'ECDHE-RSA-AES256-SHA:'.
            'ECDHE-RSA-AES128-SHA:'.
            'ECDHE-RSA-RC4-SHA:'.
            'DHE-RSA-AES256-GCM-SHA384:'.
            'DHE-RSA-AES128-GCM-SHA256:'.
            'DHE-RSA-AES256-SHA256:'.
            'DHE-RSA-AES128-SHA256:'.
            'DHE-RSA-AES256-SHA:'.
            'DHE-RSA-AES128-SHA:'.
            'AES256-GCM-SHA384:'.
            'AES128-GCM-SHA256:'.
            'AES256-SHA256:'.
            'AES128-SHA256:'.
            'AES256-SHA:'.
            'AES128-SHA'
        );

        $this->set(CURLOPT_CAINFO, __DIR__.'/certs/trusted-certs.crt'); // Certificate file location
        $this->set(CURLOPT_CAPATH, __DIR__.'/certs/'); // Certificate folder. Need to specify it to avoid using system default certs on some platforms

        // Limit vulnerability surface area.  Supported in cURL 7.19.4+
        if (defined('CURLOPT_PROTOCOLS')) $this->set(CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        if (defined('CURLOPT_REDIR_PROTOCOLS')) $this->set(CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
    }

    /**
     * @param string $header
     */
    function addHeader($header)
    {
        $this->headers[] = $header;
    }

    function exec()
    {
        $this->set(CURLOPT_HTTPHEADER, $this->headers);

        $body = curl_exec($this->handle);
        if ($body === false) {
            throw new Exception_NetworkIO("Error executing HTTP request: " . curl_error($this->handle));
        }

        $statusCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);

        return new HttpResponse($statusCode, $body);
    }

    /**
     * @param int $option
     * @param mixed $value
     */
    function set($option, $value)
    {
        curl_setopt($this->handle, $option, $value);
    }

    function __destruct()
    {
        curl_close($this->handle);
    }
}
