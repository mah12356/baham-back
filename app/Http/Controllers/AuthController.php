<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Helper\Sms;
use App\Jobs\CreateHost;
use App\Jobs\CreateUser;
use App\Models\City;
use App\Models\Host;
use App\Models\Like;
use App\Models\State;
use App\Models\U_wallets;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function getSc(){
        return State::with('city')->get();
    }
    function registerHost(Request $request){
        $validator=Validator::make($request->all(),[
            'username' => 'required|string',
            'phone'=>'required|min:11|max:11|unique:hosts',
            'national_code'=>'required|unique:hosts|min:10|max:10',
            'password'=>'required|min:8',
            'address'=>'required|unique:hosts',
            'city'=>'required',
            'state'=>'required',
            'area'=>'required'
        ],[
            'username.required'=>'نام محل میزبانی الزامیست',
            'city.required'=>'شهری که در آن میزبان هستید الزامیست',
            'address.required'=>'ادرس کافه الزامیست',
            'address.unique'=>'آدرس قبلا ثبت شده',
            'phone.required'=>'شماره موبایل الزامیست',
            'national_code.unique'=>'کدملی قبلا ثبت شده',
            'national_code.required'=>"کدملی رو یادت رفت",
            'national_code.min'=>'حداقل و حداکثر تعداد ارقام کدملی ۱۰ رقم باید باشد',
            'national_code.max'=>'حداقل و حداکثر تعداد ارقام کدملی ۱۰ رقم باید باشد',
            'password.required'=>'پسورد رو یادت رفت',
            'password.min'=>'پسورد باید حداقل هشت کاراکتر باشد',
            'state.required'=>'منطقه ای که میزبان هستید الزامیست',
            'phone.min'=>'شماره موبایل باید 11 رقم باشد',
            'phone.max'=>'شماره موبایل باید 11 رقم باشد',
            'phone.unique'=>'این شماره موبایل قبلا ثبت شده'
        ]
        );
        $phone=Helper::phone($request->phone,$request->national_code);
        if ($phone!==true){
            return response()->json(['message'=>'این شماره موبایل و کد ملی باهم همخوانی ندارند'],403);
        }
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }else{
            $state=State::where('name',$request->state)->first();
            if ($state===null){
                return response()->json(['message'=>'چنین استانی پیدا نشد'],403);
            }
            $city=City::where(['name'=>$request->city,'state_id'=>$state->id])->first();
            if ($city===null){
                return response()->json(['message'=>'شهر پیدا نشد'],403);
            }
            $i=Helper::verifyLoc($request->area,$request->city,$request->state);
            if($i===1){
                (new CreateHost($request))->handle();
                $token=auth('host')->login(Session::get('host'));
                return $this->respondWithToken($token,'host');
            }else{
                return response()->json(['message'=>'این شهر محله ای با این اسم ندارد'],403);
            }
        }
    }
    function loginHost(Request $request){
        $token=auth('host')->attempt($request->only('national_code','password'));
        if (!$token){
            return response()->json(['message'=>'کدملی یا پسورد نادرست است'],422);
        }else{
            return $this->respondWithToken($token,'host');
        }
    }
    function registerUser(Request $request){
        $validate=Validator::make($request->all(),[
            'national_code'=>'required|unique:users',
            'phone'=>'required|min:11|max:11|unique:users',
            'password'=>'required|min:8'
        ],[
            'phone.required'=>'موبایل الزامیست',
            'national_code.required'=>'نام میزبان الزامیست',
            'national_code.unique'=>'این نام قبلا ثبت شده',
            'phone.min'=>'شماره موبایل باید 11 رقم باشد',
            'phone.max'=>'شماره موبایل باید 11 رقم باشد',
            'phone.unique'=>'این شماره موبایل قبلا ثبت شده',
            'password.required'=>'گذرواژه الزامیست',
            'password.min'=>'گذرواژه باید حداقل 8 کاراکتر باشد',
        ]);
        if ($validate->fails()){
            return response()->json($validate->errors(),422);
        }else{
            // ساخت کاربر به همراه کیف پول
            $data=$request->except('password');
            $data['password']=Hash::make($request->password);
            $user=User::create($data);
            $wallet=new U_wallets();
            $wallet->user_id=$user->id;
            $wallet->save();
            $token=auth('api')->login($user);
            return $this->respondWithToken($token,'api');
        }
    }
    function loginUser(Request $request){
        $token=auth('api')->attempt($request->only('national_code','password'));
        if (!$token){
            return response()->json(['message'=>'شماره موبایل با گذرواژه همخوانی ندارند'],422);
        }else{
            return $this->respondWithToken($token,'api');
        }
    }
    protected function respondWithToken($token,$type){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($type)->factory()->getTTL() * 60*24*7
        ]);
    }
    function logout(){
        auth('api')->logout();
        auth('host')->logout();
    }
    function user(){
        return Helper::getUser();
    }

    function changePasswordStepOne(Request $req){
        $validator=Validator::make($req->all(),[
            'phone'=>'required'
        ],[
            'phone.required'=>'شماره تماس رو یادت رفت'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        $user=User::where('phone',$req->phone)->first()??Host::where('phone',$req->phone)->first();
        if ($user===null){
            return response()->json(['message'=>''],404);
        }else{
            return response()->json(['user'=>$user]);
        }
    }

    function changePasswordStepTwo(Request $req){
        if ($req->type==='host'){
            $user=Host::find($req->id);
        }else{
            $user=User::find($req->id);
        }
        $user->password=Hash::make($req->password);
        if ($user->save()){
            return response()->json(['message'=>'']);
        }else{
            return response()->json(['message'=>''],422);
        }
    }
    function shaba(Request $req){
        $validator=Validator::make($req->all(),[
            'shaba'=>'required|min:24|max:24'
        ],[
            'shaba.min'=>'حداقل و حداکثر تعداد رقم شماره شبا باید 24 باشد',
            'shaba.max'=>'حداقل و حداکثر تعداد رقم شماره شبا باید 24 باشد'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),422);
        }else{
            $usertype=auth('api')->user()??auth('host')->user();
            $shaba=Helper::shaba($req->shaba,$usertype);
            if ($shaba!==true){
                return response()->json(['message'=>'این شماره شبا متعلق کدملی شما نیست'],422);
            }else{
                $usertype->shaba=$req->shaba;
                $usertype->save();
                return response()->json(['message'=>'']);
            }
        }
    }
    function editHost(Request $req){
        $host=auth('host')->user();
        if($req->name!==null){
            $host->name=$req->name;
        }
        if ($req->city !==null && $req->state!==null){
            $verifyLoc=Helper::verifyLoc($req->area,$req->city,$req->state);
            if ($verifyLoc===false){
                return response()->json(['message'=>'بدلیل قطعی اینترنت ثبت نام میزبان انجام نشد'],422);
            }else{
                $host->address=$req->address;
                $host->city=$req->city;
                $host->state=$req->state;
                $likes=Like::where('host_id',$host->id)->get();
                foreach ($likes as $like){
                    $like->delete();
                }
                /// اگر میزبان بخواد آدرس میزبانیش رو عوض کنه امتیاز هاش رو از دست میده
                $host->likes=0;
            }
        }
        if ($req->hasFile('file')){
            $time=time();
            $photo=$this->request->file('file');
            $filename=$time.$photo->getClientOriginalName();
            Sms::checkSizeOfStorage();
            Storage::disk('s3')->delete('/host'.$filename);
            Storage::disk('s3')->putFileAs('host', $photo,$filename
            );
            $host->photo=$filename;
        }
        $host->save();
        return response()->json(['message'=>'تغییرات انجام شد']);

    }
}
