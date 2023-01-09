<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StorageLinker extends Model
{
    public StorageLinker $storageLinker;
    protected $table = 'storage_linkers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'original',
        'hash',
    ];


    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);
        $input = $attributes['filename'];
        $extension = $attributes['extension'];

        $this->attributes['original'] = $input . "." . $extension;
        $hash = Hash::make($input);
        $this->attributes['hash'] = $hash . "." . $extension;
        $this->save();
    }


    /**
     * @param Request $request
     * @return void
     */
    public static function validate(Request $request)
    {
        $request->validate([
            'original' => 'required|string',
            'hash' => 'required|string'
        ]);
    }

    public function getHash()
    {
        return $this->attributes['hash'];
    }

    public function createStorageLink(array $attributes = [])
    {
        $input = $attributes['filename'];
        $extension = $attributes['extension'];
        $hash = Hash::make($input);

        return StorageLinker::create([
            'original' => $input . "." . $extension,
            'hash' => $hash . "." . $extension,
        ]);
    }
}
