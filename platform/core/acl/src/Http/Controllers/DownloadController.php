<?php

namespace Botble\ACL\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Botble\RealEstate\IksaMedia;

class DownloadController extends BaseController
{
  /**
   * Download your file
   *
   * @param  int  $id
   * @return \Illuminate\View\View
   */
  public function downloadFile($folder, $id, $fileName, BaseHttpResponse $response)
  {
    try {
      $file =  IksaMedia::handleFileDownload($fileName, $folder, $id);
    } catch (\Exception $e) {
      return [
          'error'   => true,
          'message' => $e->getMessage(),
      ];
    }

    if (gettype($file) === 'object' || !$file['error']) {
      return $file;
    } else {
      return $response
        ->setError()
        ->setCode(404)
        ->setMessage(__($file['message']));
    }
  }
}
