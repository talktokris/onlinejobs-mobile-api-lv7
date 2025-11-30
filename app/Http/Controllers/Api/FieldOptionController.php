<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facilities;
use App\Models\Language;
use App\Models\Option;
use App\Models\Sector;
use App\Models\Religion;
use App\Models\SubSector;
use App\Models\Skill;
use App\Models\EducationLevel;
use App\Models\Gender;

class FieldOptionController extends Controller
{
    public function index()
    {
        $JobVacancyType=['Permanent','Part-Time','Contract'];
        $OfferedCurrencies=['RM','BDT','USD'];
        $OfferedPeriod=['Monthly','Yearly'];
        $WorkingHours=['Normal','Shift','Flexi-Time'];
        $genderPerson=Gender::select('id','name')->where('status','1')->get();
        $genderVacancy=['Any','Male','Female'];
        $maritalStatus=['Any','Single','Married','Divorced'];
        $race=Option::select('id','name')->where(['status'=>'1','type'=>'Job Race'])->get();
        $ageRange=['20-24','25-30','30-35','35-40','40+'];
        $facilities=Facilities::select('id','name')->where('status','1')->get();
        $language=Language::select('id','name')->where('status','1')->get();
        $reading=['Fluent','Good','Poor'];
        $speaking=['Fluent','Good','Poor'];
        $writing=['Fluent','Good','Poor'];
        $academicQualification=Option::select('id','name')->where(['status'=>'1','type'=>'Job Academic Qualification'])->get();
        $academicField=Option::select('id','name')->where(['status'=>'1','type'=>'Job Academic Field'])->get();
        $sectors = Sector::where('status','1')->get();
        $religions = Religion::where('status','1')->get();
        $subSector=SubSector::select('id','sector_id','name','slug')->where('status','1')->get();
        $skills = Skill::select('id','name','slug','type')->where('status','1')->where('for', 'gw')->where('type','Skill')->get();
        $education_levels = EducationLevel::select('id','name')->where('status','1')->get();
        return response()->json([
            'success'=>true,
            'data'=>[
                'vacancyTypes'=>$JobVacancyType,
                'offeredCurrencies'=>$OfferedCurrencies,
                'offeredPeriod'=>$OfferedPeriod,
                'workingHours'=>$WorkingHours,
                'genderPerson'=>$genderPerson,
                'genderVacancy'=>$genderVacancy,
                'maritalStatus'=>$maritalStatus,
                'race'=>$race,
                'ageRange'=>$ageRange,
                'facilities'=>$facilities,
                'reading'=>$reading,
                'language'=>$language,
                'speaking'=>$speaking,
                'writing'=>$writing,
                'academicQualification'=>$academicQualification,
                'academicField'=>$academicField,
                'sectors'=>$sectors,
                'religions'=>$religions,
                'subSector'=>$subSector,
                'skills'=>$skills,
                'education_levels'=>$education_levels
            ],
            'message'=>'Options data fetch success'
        ],200);
    }
}