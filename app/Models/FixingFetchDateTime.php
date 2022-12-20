<?php

namespace App\Models;

use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;

trait FixingFetchDateTime
{
    /**
     * @inheritdoc
     */
    protected function asDateTime($value)
    {
        if ($value instanceof UTCDateTime) {
            $date = $value->toDateTime();
            return Carbon::parse($date->format($this->getDateFormat()));
        }

        return parent::asDateTime($value);
    }
}
