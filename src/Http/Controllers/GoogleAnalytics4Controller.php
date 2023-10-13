<?php

namespace Panchania83\LaravelNovaGoogleAnalytics4\Http\Controllers;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class GoogleAnalytics4Controller extends Controller
{

    public function __invoke()
    {
        return $this->ga4_most_visited_pages();
    }

    public function ga4_most_visited_pages()
    {
        /**
         * TODO(developer): Replace this variable with your Google Analytics 4
         *   property ID before running the sample.
         */
        $property_id = config('analytics.property_id');
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . config('analytics.service_account_credentials_json'));
        // Using a default constructor instructs the client to use the credentials
        // specified in GOOGLE_APPLICATION_CREDENTIALS environment variable.
        $client = new BetaAnalyticsDataClient();

        try {
            // Make an API call.
            $response = $client->runReport([
                'property' => 'properties/' . $property_id,
                'dateRanges' => [
                    new DateRange([
                        'start_date' => Carbon::now()->subDay(7)->toDateString(),
                        'end_date' => 'today',
                    ]),
                ],
                'dimensions' => [
                    (new Dimension())->setName('pageTitle'),
                    (new Dimension())->setName('hostName'),
                    (new Dimension())->setName('pagePath'),
                ],
                'metrics' => [
                    (new Metric())->setName('totalUsers'),
                ],
                'limit' => 5,
            ]);

            // Print results of an API call.
            $dataga4 = [];

            foreach ($response->getRows() as $row) {

                $darwew['pagetitle'] = $row->getDimensionValues()[0]->getValue();
                $darwew['hostname'] = $row->getDimensionValues()[1]->getValue();
                $darwew['pagepath'] = $row->getDimensionValues()[2]->getValue();
                $darwew['totalusers'] = $row->getMetricValues()[0]->getValue();

                $dataga4[] = $darwew;
            }
           
            return response()->json($dataga4);
        } catch (\Exception $e) {
            // Handle any errors
            echo 'An error occurred: ' . $e->getMessage();
        }
    }
}
