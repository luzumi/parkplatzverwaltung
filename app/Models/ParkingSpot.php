<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    /**
     * CAR ATTRIBUTES
     * $this->attributes['id'] - int - contains the parking_spot primary key
     * $this->attributes['number'] - string - contains the parking_spot number
     * $this->attributes['row'] - string - contains the parking_spot row
     * $this->attributes['image'] - string - contains the parking_spot image
     * $this->attributes['status'] - string - contains the parking_spot status
     * $this->attributes['created_at'] - timestamp - contains the parking_spot creation date
     * $this->attributes['updated_at'] - timestamp - contains the parking_spot updated date
     *
     */

    protected $fillable = ['number', 'row', 'image', 'status'];

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    public function getNumber()
    {
        return $this->attributes['number'];
    }

    public function setNumber($number)
    {
        $this->attributes['number'] = $number;
    }

    public function getRow()
    {
        return $this->attributes['row'];
    }

    public function setRow($row)
    {
        $this->attributes['row'] = $row;
    }

    public function getImage()
    {
        return $this->attributes['image'];
    }

    public function setImage($image)
    {
        $this->attributes['image'] = $image;
    }

    public function getStatus()
    {
        return $this->attributes['status'];
    }

    public function setStatus($status)
    {
        $this->attributes['status'] = $status;
    }

    public function getCreatedAt()
    {
        return $this->attributes['created_at'];
    }

    public function setCreatedAt($createdAt)
    {
        $this->attributes['created_at'] = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->attributes['updated_at'];
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->attributes['updated_at'] = $updatedAt;
    }

}