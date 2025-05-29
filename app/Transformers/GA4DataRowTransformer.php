<?php
namespace App\Transformers;

use Google\Analytics\Data\V1beta\RunReportResponse;

class GA4DataRowTransformer
{
    protected $metrics;
    protected $dimensions;

    public function __construct()
    {
        $this->metrics = [];
        $this->dimensions = [];
    }

    public function transform($response)
    {
        $rows = $response->getRows();

        $dim_headers = $response->getDimensionHeaders();

        $metric_headers = $response->getMetricHeaders();

        foreach($rows as $row) {

            $this->dimensions[] = $this->getRowValues($dim_headers, $row->getDimensionValues());

            $this->metrics[] = $this->getRowValues($metric_headers, $row->getMetricValues());
        
        }

        return ['metrics' => $this->metrics, 'dimensions' => $this->dimensions];
    }

    protected function getRowValues($headers, $rows) 
    {
        $result = [];

        foreach($rows as $index => $row)
        {
            $header = $headers[$index]->getName(); 
            
            if($header == 'eventCount') {

                $count = count($this->dimensions);
                
                $count = $count == 0 ? 0 : $count - 1;

                $header = $this->dimensions[$count]['eventName'];

                unset($this->dimensions[$count]['eventName']);
            }

            $result[$header] = $row->getValue();
        }

        return $result;
    }
}
