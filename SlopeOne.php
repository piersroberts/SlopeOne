<?php
class SlopeOne{
    private $diffs = [];
    private $freqs = [];
    
    public function predict($userPrefs = []){
        $preds = [];
        $freqs = [];
        $results = [];

        foreach ($userPrefs as $item=>$rating){
            foreach($this->diffs as $diffItem=>$diffRatings){
                if(isset($this->freqs[$diffItem]) && isset($this->freqs[$diffItem][$item])){
                    $freq = $this->freqs[$diffItem][$item];
                    isset($preds[$diffItem]) || $preds[$diffItem] = 0.0;
                    isset($freqs[$diffItem]) || $freqs[$diffItem] = 0;
                    $preds[$diffItem] += $freq * ($diffRatings[$item] + $rating);
                    $freqs[$diffItem] += $freq;                    
                }
                
            }
        }
        foreach($preds as $item => $value){
            if (!isset($userPrefs[$item]) && $freqs[$item] > 0){
                $results[] = [$item=>$value/$freqs[$item]];
            }
        }
        return $results;
    }
    public function update($userData){
        foreach($userData as $ratings){
            foreach($ratings as $item1=>$rating1){
                isset($this->freqs[$item1]) || $this->freqs[$item1] = [];
                isset($this->diffs[$item1]) || $this->diffs[$item1] = [];

                foreach($ratings as $item2=>$rating2){
                    isset($this->freqs[$item1][$item2]) || $this->freqs[$item1][$item2] = 0;
                    isset($this->diffs[$item1][$item2]) || $this->diffs[$item1][$item2] = 0.0;
                    $this->freqs[$item1][$item2] += 1;
                    $this->diffs[$item1][$item2] += $rating1 - $rating2;
                }
            }
        }
        foreach($this->diffs as $item1 => &$ratings){
            foreach($ratings as $item2=>$rating){
                $ratings[$item2] /= $this->freqs[$item1][$item2];
            }
        }
    }
}