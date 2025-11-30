<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Language;
use App\Models\Religion;
use App\Models\Option;
use App\Models\EducationLevel;
use App\Models\Skill;
use App\Models\SkillLevel;
use App\Models\Qualification;

use App\Http\Resources\CountryResource;
use App\Http\Resources\GenderResource;
use App\Http\Resources\MaritalStatusResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\ReligionResource;
use App\Http\Resources\OptionResource;
use App\Http\Resources\EducationLevelResource;
use App\Http\Resources\SkillLevelResource;
use App\Http\Resources\QualificationResource;
use App\Http\Resources\SkillResource;



class FillsAppController extends Controller
{
    //
    public function status(){



        $Country = Country::where('status','=',1)->get();
        $Gender = Gender::where('status','=',1)->get();
        $MaritalStatus = MaritalStatus::where('status','=',1)->get();
        $Language = Language::where('status','=',1)->get();
        $Religion = Religion::where('status','=',1)->get();
        $Option = Option::where('status','=',1)->get();
        $EducationLevel = EducationLevel::where('status','=',1)->get();
        $SkillLevel = SkillLevel::where('status','=',1)->get();
        $Skills = Skill::where('status','=',1)->where('type', '=', 'Skill')->get();



        return [
            'status' => 'success',
            'Country' => CountryResource::collection($Country),
            'Gender' => GenderResource::collection($Gender),
            'MaritalStatus' => MaritalStatusResource::collection($MaritalStatus),
            'Language' => LanguageResource::collection($Language),
            'Religion' => ReligionResource::collection($Religion),
            'Option' => OptionResource::collection($Option),
            'EducationLevel' => EducationLevelResource::collection($EducationLevel),
            'SkillLevel' => SkillLevelResource::collection($SkillLevel),
            'Skills' => SkillResource::collection($Skills),

        ];
    }
}