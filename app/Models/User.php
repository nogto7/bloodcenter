<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

// use App\Models\User;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = Hash::make($value);
    // }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    // $user = User::create([
    //     'name' => 'admin',
    //     'email' => 'nogto8888@gmail.com',
    //     'password' => bcrypt('123'), // нууц үг
    // ]);

    // use App\Models\User;
    // use Illuminate\Support\Facades\Hash;

    // User::create([
    //     'name' => 'Admin User',
    //     'email' => 'nogto8888@gmail.com',
    //     'password' => Hash::make('123'),
    //     'is_admin' => 1,
    // ]);

    // public function run(): void
    // {
    //     $user = User::create([
    //         'name' => 'admin',
    //         'email' => 'nogto8888@gmail.com',
    //         'password' => Hash::make('123'),
    //         'role' => 'admin',
    //     ]);

    //     User::create([
    //         'name' => 'admin',
    //         'email' => 'nogto8888@gmail.com',
    //         'password' => bcrypt('123'),
    //         'role' => 'admin',
    //     ]);

    //     User::create([
    //         'name' => 'editor',
    //         'email' => 'editor@gmail.com',
    //         'password' => bcrypt('123'),
    //         'role' => 'editor',
    //     ]);

    // $user->assignRole('admin'); // ✅ ЗӨВ ГАЗАР
    // }
}
