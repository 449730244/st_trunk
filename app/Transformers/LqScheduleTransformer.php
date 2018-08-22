<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 11:15
 */
namespace  App\Transformers;


use App\Models\LqSchedule;
use League\Fractal\TransformerAbstract;

class LqScheduleTransformer extends TransformerAbstract
{
    public function transform(LqSchedule $lqSchedule)
    {
        return [
            'ScheduleID' => $lqSchedule->ScheduleID,
            'SClassID' => $lqSchedule->SClassID,
            'SClassType' => $lqSchedule->SClassType,
            'SClassName_J'=> $lqSchedule->SClassName_J,
            'SClassName_F'=> $lqSchedule->SClassName_F,
            'MatchNumber'=> $lqSchedule->MatchNumber,
            'Color'=> trim($lqSchedule->Color),
            'MatchTime'=> $lqSchedule->MatchTime,
            'MatchState'=> $lqSchedule->MatchState,
            'MatchNumberTime'=> $lqSchedule->MatchNumberTime,
            'HomeTeamID' => $lqSchedule->HomeTeamID,
            'HomeTeamName_J'=> $lqSchedule->HomeTeamName_J,
            'HomeTeamName_F'=> $lqSchedule->HomeTeamName_F,
            'GuestTeamID'=> $lqSchedule->GuestTeamID,
            'GuestTeamName_J'=>$lqSchedule->GuestTeamName_J,
            'GuestTeamName_F' => $lqSchedule->GuestTeamName_F,
            'HomeTeamRank' => $lqSchedule->HomeTeamRank,
            'GuestTeamRank' => $lqSchedule->GuestTeamRank,
            'HomeTeamScore'=> $lqSchedule->HomeTeamScore,
            'GuestTeamScore'=> $lqSchedule->GuestTeamScore,
            'HomeOneScore'=> $lqSchedule->HomeOneScore,
            'HomeTwoScore'=> $lqSchedule->HomeTwoScore,
            'HomeThreeScore'=> $lqSchedule->HomeThreeScore,
            'HomeFourScore'=> $lqSchedule->HomeFourScore,
            'GuestOneScore'=> $lqSchedule->GuestOneScore,
            'GuestTwoScore' => $lqSchedule->GuestTwoScore,
            'GuestThreeScore'=> $lqSchedule->GuestThreeScore,
            'GuestFourScore'=> $lqSchedule->GuestFourScore,
            'OverTimeNumber'=> $lqSchedule->OverTimeNumber,
            'HomeOneOverTimeScore'=>$lqSchedule->HomeOneOverTimeScore,
            'HomeTwoOverTimeScore' => $lqSchedule->HomeTwoOverTimeScore,
            'HomeThreeOverTimeScore' => $lqSchedule->HomeThreeOverTimeScore,
            'GuestOneOverTimeScore' => $lqSchedule->GuestOneOverTimeScore,
            'GuestTwoOverTimeScore'=> $lqSchedule->GuestTwoOverTimeScore,
            'GuestThreeOverTimeScore'=> $lqSchedule->GuestThreeOverTimeScore,
            'TechnicalStatistics'=> $lqSchedule->TechnicalStatistics,
            'TVShow'=> $lqSchedule->TVShow,
            'TVRemark'=> $lqSchedule->TVRemark,
            'Neutral'=> $lqSchedule->Neutral,
            'Season'=> $lqSchedule->Season,
            'MatchAddress' => $lqSchedule->MatchAddress,
            'MatchType'=> $lqSchedule->MatchType,
            'MatchCate'=> $lqSchedule->MatchCate,
            'MatchSubSClass'=> $lqSchedule->MatchSubSClass
        ];
    }
}