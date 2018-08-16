<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ZqSubSclass;


class ZqSubSclassTransformer extends TransformerAbstract
{
    public function transform(ZqSubSclass $subSclass){
        return [
            'SubSclassID' => $subSclass->SubSclassID,
            'IsHaveScore' => $subSclass->IsHaveScore,
            'sortNumber' => $subSclass->sortNumber,
            'Curr_round' => $subSclass->Curr_round,
            'Count_round' => $subSclass->Count_round,
            'IsCurrentSclass' => $subSclass->IsCurrentSclass,
            'subSclassName' => $subSclass->subSclassName,
            'subName_JS' => $subSclass->subName_JS,
            'SubName_Es' => $subSclass->SubName_Es,
            'subName_Fs' => $subSclass->subName_Fs,
            'includeSeason' => $subSclass->includeSeason,
            'IsZu' => $subSclass->IsZu,
            'groupNum' => $subSclass->groupNum,
            'MatchSeason' => $subSclass->MatchSeason,
            'updated_at' => $subSclass->updated_at->toDateTimeString(),
        ];
    }
}