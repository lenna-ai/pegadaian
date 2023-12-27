<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CountingData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*      version="1.0.0",
*      title="Dokumentasi API Pegadaian",
*      description="created by nur arifin. Please you can use all api but you must be remove Content-fuckin-type/Content-Type in header",
*      @OA\Contact(
*          email="arifin@lenna.ai"
*      ),
*      @OA\License(
*          name="Apache 2.0",
*          url="http://www.apache.org/licenses/LICENSE-2.0.html"
*      )
* )
*
* @OA\Server(
*      url=L5_SWAGGER_CONST_HOST,
*      description="Demo API Server"
* )
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,CountingData;

    // protected function countDurationStatus(Collection $model):int {
    //     $duration = 0;

    //     foreach ($model as $key => $value) {
    //         $duration += $value->duration;
    //     }

    //     return $duration;
    // }
}
