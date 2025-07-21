<?php

namespace App\Jobs\Analytics;

use App\Models\CountryAnalytics;
use App\Models\KeyMetric;
use App\Models\PageAnalytic;
use App\Models\TrafficSource;
use App\Services\GoogleAnalyticsService;
use App\Transformers\GA4DataRowTransformer;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncGA4AnalyticsDaily implements ShouldQueue
{
    use Queueable;

    protected $from;
    protected $to;

    /**
     * Create a new job instance.
     */
    public function __construct($from = '', $to = '')
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleAnalyticsService $service): void
    {
        $this->from = !$this->from ? Carbon::now()->subDay()->format('Y-m-d') : $this->from;
        $this->to = !$this->to ? Carbon::now()->subDay()->format('Y-m-d') : $this->to;
        
        $keyMetrics = $service->getData(new GA4DataRowTransformer, $this->from, $this->to, 'key_metrics');

        foreach($keyMetrics['metrics'] as $index => $row)
        {
            KeyMetric::insertRows($row, $keyMetrics['dimensions'][$index]);
        }

        if(count($keyMetrics['metrics']) > 0) {
            KeyMetric::where('dateHour', 'like', '%'.$this->from.'%');
        }

        $trafficSources = $service->getData(new GA4DataRowTransformer, $this->from, $this->to, 'traffic_sources');

        foreach($trafficSources['metrics'] as $index => $source) 
        {
            TrafficSource::insertRow($row, $trafficSources['dimensions'][$index]);
        }

        if(count($trafficSources['metrics']) > 0) {
            TrafficSource::where('dateHour', 'like', '%'.$this->from.'%');
        }

        $countryAnalytics = $service->getData(new GA4DataRowTransformer, $this->from, $this->to, 'country_analytics');
 
        foreach($countryAnalytics['metrics'] as $index => $row)
        {
            CountryAnalytics::insertRow($row, $countryAnalytics['dimensions'][$index]);
        } 

        if(count($countryAnalytics['metrics']) > 0) {
            CountryAnalytics::where('dateHour', 'like', '%'.$this->from.'%');
        }

        $pageAnalytics = $service->getData(new GA4DataRowTransformer, $this->from, $this->to, 'page_analytics');

        foreach($pageAnalytics['metrics'] as $index => $row)
        {
            PageAnalytic::insertRow($row, $pageAnalytics['dimensions'][$index]);
        } 

        if(count($pageAnalytics['metrics']) > 0) {
            PageAnalytic::where('dateHour', 'like', '%'.$this->from.'%');
        }
    }
}
 