<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'deadline_at',
        'support_at',
        'priority',
        'status',
    ];
    protected $casts = [
        'deadline_at' => 'datetime',
        'support_at'  => 'datetime',
        //'created_at'  => 'datetime',
        //'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    public function saveTask(Request $request, $update = null)
    {
        $this->title = $request->input('title');
        $this->content = $request->input('content');
        $this->deadline_at = $request->input('deadline_at');
        $this->support_at = !empty($request->input('support_at')) ? $request->input('support_at') : null;
        $this->priority = $request->input('priority');
        $this->status = $request->input('status');
        $this->updated_at = $update;
        $this->save();
    }
}
