<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Facilities;
use App\Models\Language;

class JobSeekerController extends Controller
{
    public function index()
    {
        // return Country::where('id',1)->get();
        $position=Option::select('id','name')->where(['status'=>'1','type'=>'Position Name'])->get();
        $JobVacancyType=['Permanent','Part-Time','Contract'];
        $OfferedCurrencies=['RM','BDT','USD'];
        $OfferedPeriod=['Monthly','Yearly'];
        $WorkingHours=['Normal','Shift','Flexi-Time'];
        $gender=['Any','Male','Female'];
        $maritalStatus=['Any','Single','Married','Divorced'];
        $race=Option::select('id','name')->where(['status'=>'1','type'=>'Job Race'])->get();
        $ageRange=['20-24','25-30','30-35','35-40','40+'];
        $facilities=Facilities::select('id','name')->where('status','1')->get();
        $language=Language::select('id','name')->where('status','1')->get();
        $speaking=['Fluent','Good','Poor'];
        $writing=['Fluent','Good','Poor'];
        $academicQualification=Option::select('id','name')->where(['status'=>'1','type'=>'Job Academic Qualification'])->get();
        $academicField=Option::select('id','name')->where(['status'=>'1','type'=>'Job Academic Field'])->get();
        return response()->json([
            'success'=>'true',
            'data'=>[
                'positions'=>$position,
                'vacancyTypes'=>$JobVacancyType,
                'offeredCurrencies'=>$OfferedCurrencies,
                'offeredPeriod'=>$OfferedPeriod,
                'workingHours'=>$WorkingHours,
                'gender'=>$gender,
                'maritalStatus'=>$maritalStatus,
                'race'=>$race,
                'ageRange'=>$ageRange,
                'facilities'=>$facilities,
                'language'=>$language,
                'speaking'=>$speaking,
                'writing'=>$writing,
                'academicQualification'=>$academicQualification,
                'academicField'=>$academicField

            ],
            'message'=>'Country data fetch success'
        ],200);
    }
}