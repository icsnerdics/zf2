<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Service
 */

namespace Zend\Service\Nirvanix\Context;

use Zend\Http\Request as HttpRequest;
use Zend\Service\Nirvanix\Response;

/**
 * Namespace proxy with additional convenience methods for the IMFS namespace.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Nirvanix
 */
class Imfs extends Base
{
    /**
     * Convenience function to get the contents of a file on
     * the Nirvanix IMFS.  Analog to PHP's file_get_contents().
     *
     * @param  string  $filePath    Remote path and filename
     * @param  integer $expiration  Number of seconds that Nirvanix
     *                              make the file available for download.
     * @return string               Contents of file
     */
    public function getContents($filePath, $expiration = 3600)
    {
        // get url to download the file
        $params = array(
            'filePath'   => $filePath,
            'expiration' => $expiration,
        );
        $resp = $this->getOptimalUrls($params);
        $url  = (string)$resp->Download->DownloadURL;

        // download the file
        $client = $this->httpClient;
        $client->resetParameters();
        $client->setUri($url);
        $client->setMethod(HttpRequest::METHOD_GET);

        $resp = $client->send();

        return $resp->getBody();
    }

    /**
     * Convenience function to put the contents of a string into
     * the Nirvanix IMFS.  Analog to PHP's file_put_contents().
     *
     * @param  string  $filePath    Remote path and filename
     * @param  integer $data        Data to store in the file
     * @param  string  $mimeType    Mime type of data
     * @return Response
     */
    public function putContents($filePath, $data, $mimeType = null)
    {
        // get storage node for upload
        $params      = array('sizeBytes' => strlen($data));
        $resp        = $this->getStorageNode($params);
        $host        = (string) $resp->GetStorageNode->UploadHost;
        $uploadToken = (string) $resp->GetStorageNode->UploadToken;
        $client      = $this->httpClient;

        // http upload data into remote file
        $client->resetParameters();
        $client->setUri("http://{$host}/Upload.ashx");
        $client->setMethod(HttpRequest::METHOD_POST);
        $client->setParameterPost(array(
            'uploadToken'    => $uploadToken,
            'destFolderPath' => str_replace('\\', '/',dirname($filePath)),
        ));
        $client->setFileUpload(basename($filePath), 'uploadFile', $data, $mimeType);
        $response = $client->send();

        return new Response($response->getBody());
    }

    /**
     * Convenience function to remove a file from the Nirvanix IMFS.
     * Analog to PHP's unlink().
     *
     * @param  string  $filePath  Remove path and filename
     * @return Response
     */
    public function unlink($filePath)
    {
        $params = array('filePath' => $filePath);
        return $this->deleteFiles($params);
    }
}
