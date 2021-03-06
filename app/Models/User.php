<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Foundation\Auth\User as Authenticatable;
use  Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;
    use Notifiable{
        notify as protected laravelNotify;
    }
    use ActiveUserHelper;
    use LastActivedAtHelper;
  public function notify($instance)
  {
      if ($this->id==Auth::id()){
          return;
      }
      $this->increment('notification_count');
      $this->laravelNotify($instance);
  }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar','phone','weixin_openid','weixin_unionid',
        'weapp_openid','weixin_session_key'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function isAuthorOf($model)
    {
        return $this->id==$model->user_id;
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function markAsRead()
    {
        $this->notification_count=0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


    public function setPasswordAttribute($value)
    {
       if (strlen($value)!=60)
       {
           $value=bcrypt($value);
       }
       $this->attributes['password']=$value;
    }

    public function setAvatarAttribute($path)
    {
        if (!starts_with($path,'http')){
            $path=config('app.url')."/uploads/images/avatar/$path";
        }
        $this->attributes['avatar']=$path;
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
