<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NewUserCreated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\TaskProgress;

class ProjectController extends Controller
{
    public function getProject(Request $request,$slug){
        $project=Project::with(['tasks.task_members.member'])
        ->where('projects.slug',$slug)
        ->first();
        return response(['data'=>$project]);
    }

    public function index(Request $request){
        $query=$request->get('query');
        $projects=Project::with(['task_progress']);
        if(!is_null($query) && $query!==''){
            $projects->where('name','like','%' . $query .'%')
            ->orderBy('id','desc');
        return response(['data'=>$projects->paginate(10)],200);

    }


    return response(['data'=>$projects->paginate(10)],200);

    }

    public function store(Request $request){

        return DB::transaction(function() use($request){
            $fields=$request->all();

            $errors=Validator::make($fields,[
                'name' =>'required',
                // 'status'=>'required',
                'startDate'=>'required',
                'endDate'=>'required',
            ]);
            if($errors->fails()){
                return response($errors->errors()->all(),422);
            }
            $project=Project::create([
                'name'=>$fields['name'],
                'startDate'=>$fields['startDate'],
                'endDate'=>$fields['endDate'],
                'status'=>Project::NOT_STARTED,
                'slug'=>Project::createSlug($fields['name']),

            ]);

            TaskProgress::create([
                'projectId'=>$project->id,
                'pinned_on_dashboard'=>TaskProgress::NOT_PINNED_ON_DASHBOARD,
                'progress'=>TaskProgress::INITIAL_PROJECT_PERCENT,
            ]);

            return response(['message'=> 'Project Created'],200);
        });

    }
    public function update(Request $request){
        $fields=$request->all();

        $errors=Validator::make($fields,[
            'id'=>'required',
            'name' =>'required',
            'startDate'=>'required',
            'endDate'=>'required',
        ]);
        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        $project=Project::where('id',$fields['id'])->update([
            'name'=>$fields['name'],
            'startDate'=>$fields['startDate'],
            'endDate'=>$fields['endDate'],
            'slug'=>Project::createSlug($fields['name']),

        ]);

        return response(['message'=> 'Project Updated'],200);
    }

    public function pinnedProject(Request $request){
        $fields=$request->all();

        $errors=Validator::make($fields,[
            'projectId'=>'required|numeric',

        ]);
        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        TaskProgress::where('projectId',$fields['projectId'])
        ->update([
            'pinned_on_dashboard'=>TaskProgress::PINNED_ON_DASHBOARD
        ]);
        return response(['message'=>'project pinned on dashboard !']);
    }

    public function countProject(){
        $count= Project::count();
        return response(['count'=>$count]);

    }
}
