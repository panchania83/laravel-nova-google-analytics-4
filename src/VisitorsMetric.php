<?php

namespace Panchania83\LaravelNovaGoogleAnalytics4;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Carbon\Carbon;

class VisitorsMetric extends Value
{
    public $name = 'Visitors';

    public $width = '1/2';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        /**
         * TODO(developer): Replace this variable with your Google Analytics 4
         *   property ID before running the sample.
         */

        $property_id = config('analytics.property_id');

        // Using a default constructor instructs the client to use the credentials
        // specified in GOOGLE_APPLICATION_CREDENTIALS environment variable.

        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . config('analytics.service_account_credentials_json'));

        $lookups = [
            'MTD' => $this->visitorsOneMonth($property_id),
            1 => $this->visitorsOneDay($property_id),
            'YTD' => $this->visitorsOneYear($property_id),
        ];

        $data = Arr::get($lookups, $request->get('range'), ['result' => 0, 'previous' => 0]);

        return $this->result($data['result'])->previous($data['previous']);
    }

    private function visitorsOneDay($property_id)
    {
        $client = new BetaAnalyticsDataClient();

        try {
            // Make an API call.
            $response = $client->runReport([
                'property' => 'properties/' . $property_id,
                'dateRanges' => [
                    new DateRange([
                        'start_date' => Carbon::today()->subDays(1)->toDateString(),
                        'end_date' => 'today',
                    ]),
                ],
                'dimensions' => [
                    (new Dimension())->setName('day'),
                ],
                'metrics' => [
                    (new Metric())->setName('activeUsers'),
                ],
                // 'limit' => 5,
            ]);

            // Print results of an API call.
            $daydata = [];
            foreach ($response->getRows() as $row) {
                $daydata[] = $row->getMetricValues()[0]->getValue();
            }

            if (count($daydata) == 2) {
                return [
                    'previous' => $daydata[0],
                    'result' => $daydata[1],
                ];
            } else if (count($daydata) == 1) {
                return [
                    'previous' => $daydata[0],
                    'result' => 0,
                ];
            } else {
                return [
                    'previous' => 0,
                    'result' => 0,
                ];
            }
        } catch (\Exception $e) {
            // Handle any errors
            echo 'An error occurred: ' . $e->getMessage();
        }
    }

    private function visitorsOneMonth($property_id)
    {
        $client = new BetaAnalyticsDataClient();

        try {
            // Make an API call.
            $response = $client->runReport([
                'property' => 'properties/' . $property_id,
                'dateRanges' => [
                    new DateRange([
                        'start_date' => Carbon::today()->subMonths(1)->startOfDay()->toDateString(),
                        'end_date' => 'today',
                    ]),
                ],
                'dimensions' => [
                    (new Dimension())->setName('month'),
                ],
                'metrics' => [
                    (new Metric())->setName('activeUsers'),
                ],
                // 'limit' => 5,
            ]);

            // Print results of an API call.
            $daydata = [];
            foreach ($response->getRows() as $row) {
                $daydata[] = $row->getMetricValues()[0]->getValue();
            }

            if (count($daydata) == 2) {
                return [
                    'previous' => $daydata[0],
                    'result' => $daydata[1],
                ];
            } else if (count($daydata) == 1) {
                return [
                    'previous' => $daydata[0],
                    'result' => 0,
                ];
            } else {
                return [
                    'previous' => 0,
                    'result' => 0,
                ];
            }
        } catch (\Exception $e) {
            // Handle any errors
            echo 'An error occurred: ' . $e->getMessage();
        }
    }

    private function visitorsOneYear($property_id)
    {
        $client = new BetaAnalyticsDataClient();

        try {
            // Make an API call.
            $response = $client->runReport([
                'property' => 'properties/' . $property_id,
                'dateRanges' => [
                    new DateRange([
                        'start_date' => Carbon::today()->subYears(1)->startOfDay()->toDateString(),
                        'end_date' => 'today',
                    ]),
                ],
                'dimensions' => [
                    (new Dimension())->setName('year'),
                ],
                'metrics' => [
                    (new Metric())->setName('activeUsers'),
                ],
                // 'limit' => 5,
            ]);

            // Print results of an API call.
            $daydata = [];
            foreach ($response->getRows() as $row) {
                $daydata[] = $row->getMetricValues()[0]->getValue();
            }

            if (count($daydata) == 2) {
                return [
                    'previous' => $daydata[0],
                    'result' => $daydata[1],
                ];
            } else if (count($daydata) == 1) {
                return [
                    'previous' => $daydata[0],
                    'result' => 0,
                ];
            } else {
                return [
                    'previous' => 0,
                    'result' => 0,
                ];
            }
        } catch (\Exception $e) {
            // Handle any errors
            echo 'An error occurred: ' . $e->getMessage();
        }
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'MTD' => 'This month (to date)',
            1 => 'Today',
            'YTD' => 'This year (to date)',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(30);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'visitors';
    }
}
