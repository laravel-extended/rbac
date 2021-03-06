<?php

namespace Extended\RBAC\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;

class RoleBinding extends Model
{
    // protected $namespaced = true;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'namespace',
        'role_kind',
        'role_name',
        // 'subjects',
    ];

    public function subjects()
    {
        return $this->hasMany(RoleBindingSubject::class);

        // return $this->morphedByMany(\App\User::class, 'subject', 'role_binding_subjects');
    }

    public static function create(array $attributes = [])
    {
        return tap(static::query()->create($attributes), function ($binding) use ($attributes) {
            if (isset($attributes['subjects']) && is_array($attributes['subjects'])) {
                $binding->subjects()->createMany($attributes['subjects']);
            }
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_name', 'name');
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function scopeSubjectHasAccess($builder, $user, $resource, $verb)
    {
        $result = $builder
            ->join('role_binding_subjects', 'role_binding_subjects.role_binding_id', '=', 'role_bindings.id')
            ->join('roles', 'roles.name', '=', 'role_bindings.role_name')
            ->join('role_rules', 'role_rules.role_id', '=', 'roles.id')
            // ->where('role_bindings.role_kind', 'role')
            ->where('role_binding_subjects.subject_kind', 'user')
            ->where('role_binding_subjects.subject_id', $user->id)
            ->whereJsonContains('role_rules.resources', $resource)
            ->whereJsonContains('role_rules.verbs', $verb)
            ->count();
        // dump($verb);
        return $result > 0;
    }

    /*protected function newMorphToMany(Builder $query, Model $parent, $name, $table, $foreignPivotKey,
                                      $relatedPivotKey, $parentKey, $relatedKey,
                                      $relationName = null, $inverse = false)
    {
        return new MorphToMany($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }*/
}
