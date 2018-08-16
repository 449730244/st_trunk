<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ZqSchedule;


class ZqScheduleTransformer extends TransformerAbstract
{
    public function transform(ZqSchedule $schedule)
    {
        return [
            'ScheduleID' => $schedule->ScheduleID,
            'SclassID' => $schedule->SclassID,
            'MatchSeason' => $schedule->MatchSeason,
            'Round' => $schedule->Round,
            'Grouping' => $schedule->Grouping,
            'HomeTeamID' => $schedule->HomeTeamID,
            'GuestTeamID' => $schedule->GuestTeamID,
            'HomeTeam' => $schedule->HomeTeam,
            'GuestTeam' => $schedule->GuestTeam,
            'Neutrality' => $schedule->Neutrality,
            'MatchTime' => $schedule->MatchTime,
            'MatchTime2' => $schedule->MatchTime2,
            'Location' => $schedule->Location,
            'Home_order' => $schedule->Home_order,
            'Guest_order' => $schedule->Guest_order,
            'MatchState' => $schedule->MatchState,
            'WeatherIcon' => $schedule->WeatherIcon,
            'Weather' => $schedule->Weather,
            'Temperature' => $schedule->Temperature,
            'TV' => $schedule->TV,
            'Umpire' => $schedule->Umpire,
            'Visitor' => $schedule->Visitor,
            'HomeScore' => $schedule->HomeScore,
            'GuestScore' => $schedule->GuestScore,
            'HomeHalfScore' => $schedule->HomeHalfScore,
            'GuestHafeScore' => $schedule->GuestHafeScore,
            'Explain' => $schedule->Explain,
            'Explainlist' => $schedule->Explainlist,
            'Home_Red' => $schedule->Home_Red,
            'Guest_Red' => $schedule->Guest_Red,
            'Home_Yellow' => $schedule->Home_Yellow,
            'Guest_Yellow' => $schedule->Guest_Yellow,
            'bf_changetime' => $schedule->bf_changetime,
            'ShangPan' => $schedule->ShangPan,
            'grouping2' => $schedule->grouping2,
            'subSclassID' => $schedule->subSclassID,
            'bfShow' => $schedule->bfShow,
            'homeCorner' => $schedule->homeCorner,
            'homeCornerHalf' => $schedule->homeCornerHalf,
            'guestCorner' => $schedule->guestCorner,
            'guestCornerHalf' => $schedule->guestCornerHalf,
            'updated_at' => $schedule->updated_at->toDateTimeString(),
        ];
    }
}