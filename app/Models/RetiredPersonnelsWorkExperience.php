<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetiredPersonnelsWorkExperience extends Model
{
    use HasFactory;


    public function from_day(){

        return date('d', strtotime($this->from));

    }

    public function from_month(){

        return date('m', strtotime($this->from));

    }

    public function from_year(){

        return date('Y', strtotime($this->from));

    }

    public function to_day(){

        return date('d', strtotime($this->to));

    }

    public function to_month(){

        return date('m', strtotime($this->to));

    }

    public function to_year(){

        return date('Y', strtotime($this->to));

    }

    public function experienceLength()
    {

        $experience = Carbon::parse($this->from)->diffInMonths(Carbon::parse($this->to));

        if($experience < 12 ){

            return $experience . ' Months';

        }else{

            $year = intval($experience / 12);

            $month = $experience % 12;

            $str  = '';
            $str  = $year . ' ';
            $str .= $year > 1 ? 'Years ' : 'Year ';
            $str .= $month . ' ';
            $str .= $month > 1 ? 'Months' : 'Month';

            return $str;

        }

    }
}