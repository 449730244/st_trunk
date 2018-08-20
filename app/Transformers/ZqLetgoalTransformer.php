<?php
namespace App\Transformers;

use App\Models\ZqLetgoal;
use League\Fractal\TransformerAbstract;

class ZqLetgoalTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString(),
        ];
    }
}