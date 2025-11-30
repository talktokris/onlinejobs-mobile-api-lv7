<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserSkill extends Model
{
    use HasFactory;

    /**
     * Get all of the comments for the UserSkill
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levelInfo()
    {
        return $this->hasOne(SkillLevel::class, 'id', 'level_id');
    }
    public function skillInfo()
    {
        return $this->hasOne(Skill::class, 'id', 'skill_id');
    }

    public function skillTogether()
    
    {


    //     $recent_jobs = \DB::table('user_skills as us')
    //     ->join('skills as s', 's.id', '=', 'us.skill_id')
    //     ->join('skill_levels as l', 'l.id', '=', 'us.level_id')
    //     ->orderBy('us.id','desc')
    //     ->select(
    //         'us.id as id',
    //         'us.skill_id as skill_id',
    //         's.name as skill_name',
    //         's.type as skill_type',
    //         'us.level_id as level_id',
    //         'l.name as skill_name',
    //         'us.delete_status as delete_status',
    //         'us.status as status',
            
    //     )->get();
    //    // ->paginate(10);
    //     return $recent_jobs;
        // $level = $this->hasOne(SkillLevel::class, 'id', 'level_id');
        // $skill = $this->hasOne(Skill::class, 'id', 'skill_id');
        // return [
        //     'skill_info'=>$skill,
        //     'level_info'=>$level,

        // ];
    }
}