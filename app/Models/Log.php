<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    public function getChanges() {
        $array = [];
        if (isset($this->info)) {
            $stringArr = explode(',', $this->info);
            foreach($stringArr as $string) {
                $temp = explode('|', $string);
                $array[$temp[0]] = $temp[1];
            }
        }
        return $array;
    }
}