<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
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
            $tz = new DateTimeZone(config('app.timezone'));
            return Carbon::parse($date->setTimezone($tz)->format($this->getDateFormat()));
        }

        return parent::asDateTime($value);
    }
}
