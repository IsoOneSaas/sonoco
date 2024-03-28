<?php namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
  
class FallbackController extends Controller
{   
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __invoke()
    {		
		$exists = false;
		$contentPath = public_path() .'/tenants/sonoco/'.  config('settings.PATH_DOC_CONTENT');
		$url = url()->current();
		$exists = ( str_contains($url, 'lKN') ) ? true : $exists;
		$exists = ( str_contains($url, 'LKN') ) ? true : $exists;
		if($exists) {
			$pos = strrpos($url, '/');
			$filename = substr($url, $pos + 1);	
			$path = $contentPath . $filename;
			if(file_exists($path)) {
				Log::info('Invoke Method (1)...');
				return response()->file($path);
			 } else {
				Log::error('File did not find (1): '. $path);
				abort(404);
			}			
			
		} else {			
			$exists = ( str_contains($url, 'C:') && str_contains($url, 'mostrar') ) ? true : false;
			if($exists) {
				$pos = strrpos($url, '/');
				$filename = substr($url, $pos + 1);	
				$path = $contentPath . $filename;
				if(file_exists($path)) {
					Log::info('Invoke Method (2)...');
					return response()->file($path);
				 } else {
					Log::error('File did not find (2): '. $path);
					abort(404);
				}					
			} else {
				Log::error('File did not find (3): '. $url);
				abort(404);				
			}				
		}
        //return view('errors.fallback');
    }
}