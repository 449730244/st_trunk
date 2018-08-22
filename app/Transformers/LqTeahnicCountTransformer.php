<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;


class LqTeahnicCountTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return
             [
                "hometeamID"=>intval($data->hometeamID),
                "hometeam"=>$data->hometeam,
                "homescore"=>$data->homescore,
                "homefast"=>$data->hometeamID,
                "homeinside"=>$data->homeinside,
                "homeExceed"=>$data->homeExceed,
                "twoattack"=>$data->twoattack,
                "totalmis"=>$data->totalmis,
                "guestteamID"=>$data->guestteamID,
                "guestteam"=>$data->guestteam,
                "guestscore"=>$data->guestscore,
                "guestfast"=>$data->guestfast,
                "guestExceed"=>$data->guestExceed,
                "guestinside"=>$data->guestinside,
                "guestTwoattack"=>$data->guestTwoattack,
                "guestTotalmis"=>$data->guestTotalmis,
                "matchtime"=>$data->matchtime,
                "costtime"=>$data->costtime,
                "HomePlayerList"=>$data->HomePlayerList,
                "GuestPlayerList"=>$data->GuestPlayerList,
                "id"=>$data->id,
            ];
    }
}