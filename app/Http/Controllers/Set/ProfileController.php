<?php namespace App\Http\Controllers\Set;

use App\Interfaces\Set\ProfileRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;
use File;
use Log;

class ProfileController extends Controller
{
    protected $profileRepo;
    //private $tool;
    private $pickerFormat;
    private $imagePath;
    private $imageURI;

    public function __construct(ProfileRepositoryInterface $profileRepository) 
    {
        $this->profileRepo = $profileRepository;
        $this->pickerFormat = [
            'd'     => 'DD',
            'j'     => 'D',
            'l'     => 'D',
            'D'     => 'D',
            'm'     => 'MM',
            'n'     => 'M',
            'M'     => 'MMM',
            'F'     => 'MMMM',
            'y'     => 'YY',
            'Y'     => 'YYYY',
        ];
        $this->imagePath = public_path() .'/tenants/sonoco/images/';
        $this->imageURI = '/tenants/sonoco/images/';
    }       
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $pages = config('settings.user.pages');
        $profile = $this->profileRepo->get();
        $pickerDefault = [
            'format'    => 'YYYY-MM-DD',
            'max'       => date("Y") - 16,
            'min'       => date("Y") - 80,
            'start'     => ( isset($profile->birth) && ($profile->birth != '') ) ? 'data-start-date="'. $profile->birth .'"' : 'data-start-date=null',
        ];
        return view('settings.profile.edit', compact('profile', 'pickerDefault', 'pages'));            
    } // index Method

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) : RedirectResponse    // UpdateAdminModelReques
    {
        //Log::debug(['UPDATE PROFILE ' =>$request->all()]);
        $input = $request->all();
        unset($input['_token'], $input['_method']);
        $response = $this->profileRepo->update($id, $input);
        //return redirect()->route('perfil.index')->with($response['status'], $response['message']);  
        return back()->with($response['status'], $response['message']);  
    } // update Method 
    
    public function store(Request $request)
    {
        $response = ['success' => true, 'message' => 'Testing...'];
        $input = $request->all();
        //Log::debug(['STORE PROFILE ' => $input]);
        if( $file = $request->file('avatar') ) {
            $fileInfo = $file->getClientOriginalName();        
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
            if( ($extension == 'jpg') || ($extension == 'jpeg') ) {
                $file_name =  'avatar_'. $input['uid'] .'.'. $extension;    //
                if( $file->move($this->imagePath, $file_name) ) {
                    return response()->json(['success'=> true, 'url' => $this->imageURI . $file_name, 'message' => trans('profile.upload.success') ]);
                } else {
                    return response()->json(['success'=> false, 'message' => trans('profile.upload.no-move') ]);
                }                
            } else {
                return response()->json(['success'=> false, 'message' => trans('profile.upload.no-mime') ]);
            }
        } else {
            return response()->json(['success'=> false, 'message' => trans('profile.upload.no-file') ]);
        }
        return response()->json($response);
    } // store Method

    public function upload(Request $request)
    {
        $response = ['success' => false, 'message' => 'No se encontró imagen'];
        $input = $request->all(); 
        //Log::debug(['UPLOAD PROFILE ' => $input]);

        if( $input['signed'] !== null ) {
            $image_parts = explode(";base64,", $input['signed']);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
    
            $file_name =  'signature_'. $input['uid'] .'.'. $image_type;
            if( file_put_contents($this->imagePath . $file_name, $image_base64) ) {
                return response()->json(['success'=> true, 'url' => $this->imageURI . $file_name, 'message' => trans('profile.store.success') ]);
            } else {
                return response()->json(['success'=> false, 'message' => trans('profile.store.no-success') ]);
            }
        } // if
        return response()->json($response);
    } // upload Method

    public function image(Request $request)
    {      
        $response = ['success' => false, 'message' => trans('profile.image.no-success')];
        $input = $request->all();
        //Log::debug(['UPLOAD IMAGE ' => $input]);
        
        if ($file = $request->hasFile('image')) {
            $file = $request->file('image');
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            if( $ext == 'png' || $ext == 'PNG' ) {
                $fileName = 'signature_'. $input['uid'] .'.png';   
               //Log::debug(['FILENAME ' => $fileName, 'PATH' => $this->imagePath . $fileName]);
                if ($file->move($this->imagePath, $fileName)) {
                    $path = $this->imagePath . $fileName;
                    if( File::exists($path) ) {
                        Log::info('Archivo de imagen encontrado: '.$path);
                        $image = Image::read($path);
                        $height = $image->height();
                        $width = $image->width();
                        if( $width >= $height ) {
                            $new = round( $height * 300/$width, 0 );
                            $image->resize(300, $new);
                        } else {
                            $new = round( $width * 300/$height, 0 );
                            $image->resize($new, 300);
                        }
                        $image->save($path);
                        
                        return response()->json(['success' => true, 'url' => $this->imageURI . $fileName, 'message' => trans('profile.image.success')]);
                    } else {
                        Log::error('Archivo de imagen no encontrado');
                        return response()->json(['success' => false, 'message' => trans('profile.image.no-exists')]);
                    }
                } else {
                    Log::error('Archivo de imagen no cargado');
                    return response()->json(['success' => false, 'message' => trans('profile.image.no-move')]);
                }  
            } else {
                return response()->json(['success' => false, 'message' => trans('profile.image.no-mime')]);
            }                   
        }        
        return response()->json($response);
    } // image Method 
    
    public function destroy($id)
    {        
        $uid =  $this->profileRepo->getUid($id);
        $fileName =  'signature_'. $uid .'.png';
        //Log::debug(['FILE TO DELETE :'=> $fileName]);
        $path = $this->imagePath . $fileName;
        if( File::exists($path) ) {
            File::delete($path);
            Log::info('Delete Signature File: '. $fileName);
        } else {
            return response()->json(['success' => false, 'message' => trans('profile.delete.no-success')]);
        }
        return response()->json(['success' => true, 'message' => trans('profile.delete.success')]);
    } // delete Method

    public function password(Request $request)
    {
        //Log::debug(['PASSWORD PROFILE ' =>$request->all()]);
        $input = $request->all();
        // VALIDAR CAMBIO DE CONTRASEÑA
        if( $input['password'] != '' ) {
            if( $this->validPassword($input['password']) ) {
                if( $input['passwordR'] != '' ) {
                    if( $input['passwordR'] == $input['password'] ) {
                        //$response = ['success' => true, 'message' => trans('profile.password.success')];
                        $response = $this->profileRepo->setPassword($input);
                    } else {
                        $response = ['success' => false, 'message' => trans('profile.password.no-match')];
                    }
                } else {
                    $response = ['success' => false, 'message' => trans('profile.password.no-repeat')];
                }
            } else {
                $response = ['success' => false, 'message' => trans('profile.password.no-valid')];
            }
        } else {
            $response = ['success' => false, 'message' => trans('profile.password.no-value')];
        }
        return $response;
    } // password

    private function validPassword($string)
    {

        if (strlen($string) < 8) {
            //$passwordErr = "Your Password Must Contain At Least 8 Characters!";
            return false;
        }
        elseif(!preg_match("#[0-9]+#",$string)) {
            //$passwordErr = "Your Password Must Contain At Least 1 Number!";
            return false;
        }
        elseif(!preg_match("#[A-Z]+#",$string)) {
            //$passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
            return false;
        } else {
            return true;
        }        

    }

} // class
