<?php

namespace Dropbox;

/**
 * Peforms a few basic checks of your PHP installation's SSL implementation to see
 * if it insecure in an obvious way.
 */
class SSLTester
{
    static function test()
    {
        echo "Testing your PHP installation's SSL implementation for a few obvious problems...\n";

        echo "-----------------------------------------------------------------------------\n";
        echo "Basic SSL tests\n";
        $basicFailures = self::testMulti(array(
            array("www.dropbox.com", 'testAllowed'),
            array("www.digicert.com", 'testAllowed'),
            array("www.v.dropbox.com", 'testHostnameMismatch'),
            array("testssl-expire.disig.sk", 'testUntrustedCert'),
        ));

        echo "Pinned certificate tests\n";
        $pinnedCertFailures = self::testMulti(array(
            array("www.verisign.com", 'testUntrustedCert'),
            array("www.globalsign.fr", 'testUntrustedCert'),
        ));

        if ($basicFailures) {
            echo "-----------------------------------------------------------------------------\n";
            echo "WARNING: Your PHP installation's SSL support is COMPLETELY INSECURE.\n";
            echo "Your app's communication with the Dropbox API servers can be viewed and\n";
            echo "manipulated by others.  Try upgrading your version of PHP.\n";
            echo "-----------------------------------------------------------------------------\n";
            return false;
        }
        else if ($pinnedCertFailures) {
            echo "-----------------------------------------------------------------------------\n";
            echo "WARNING: Your PHP installation's SSL implementation doesn't support\n";
            echo "certificate pinning, which is an important security feature of the Dropbox\n";
            echo "SDK.  The root certificates embedded in the SDK are being silently ignored,\n";
            echo "which reduces the security of the communication with the Dropbox API servers.\n";
            echo "\n";
            echo "More information:\n";
            echo "https://www.owasp.org/index.php/Certificate_and_Public_Key_Pinning#What_Is_Pinning.3F\n";
            echo "-----------------------------------------------------------------------------\n";
            return false;
        }
        else {
            return true;
        }
    }

    static function testMulti($tests)
    {
        $anyFailed = false;
        foreach ($tests as $test) {
            list($host, $testType) = $test;

            echo " - ".str_pad("$testType ($host) ", 50, ".");
            $url = "https://$host/";
            $passed = self::$testType($url);
            if ($passed) {
                echo " ok\n";
            } else {
                echo " FAILED\n";
                $anyFailed = true;
            }
        }
        return $anyFailed;
    }

    static function testPinnedCert()
    {
    }

    static function testAllowed($url)
    {
        $curl = RequestUtil::mkCurl("test-ssl", $url);
        $curl->set(CURLOPT_RETURNTRANSFER, true);
        $curl->exec();
        return true;
    }

    static function testUntrustedCert($url)
    {
        return self::testDisallowed($url, 'Error executing HTTP request: SSL certificate problem, verify that the CA cert is OK');
    }

    static function testHostnameMismatch($url)
    {
        return self::testDisallowed($url, 'Error executing HTTP request: SSL certificate problem: Invalid certificate chain');
    }

    static function testDisallowed($url, $expectedExceptionMessage)
    {
        $curl = RequestUtil::mkCurl("test-ssl", $url);
        $curl->set(CURLOPT_RETURNTRANSFER, true);
        try {
            $curl->exec();
        }
        catch (Exception_NetworkIO $ex) {
            if (strpos($ex->getMessage(), $expectedExceptionMessage) == 0) {
                return true;
            } else {
                throw $ex;
            }
        }
        return false;
    }
}
