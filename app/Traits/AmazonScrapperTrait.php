<?php

namespace App\Traits;

use App\Models\ExternalReview;
use Auth;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

trait AmazonScrapperTrait

{

    use UserAgentTrait;

    public function getAmazonReviews($product_link, $product_id)
    {
        // get ASIN from URL
        $asin = $this->getASIN($product_link);
        $site = $this->getAmazonSite($product_link);
        $data = json_decode($this->getReviews($asin, $site))->results[0];
        $original_url = $product_link;
        $external_url = $data->url;
        $reviews_array = [];
        $overall_rating = 0;
        $rating_counts = [
            '5' => 0,
            '4' => 0,
            '3' => 0,
            '2' => 0,
            '1' => 0,
            '0' =>0
        ];

        //loop on scraped reviews
        foreach ($data->content->reviews as $key => $review) {
            $id = $review->id;
            $title = $review->title;
            $author = $review->author;
            $rating = $review->rating;
            $content = $review->content;
            $timestamp = $review->timestamp;
            $is_verified = $review->is_verified;
            $product_attributes = $review->product_attributes;

            //calcualte overall_rating
            $overall_rating += $review->rating;

            //save each rating count
            $rating_counts[(string)$review->rating]++;



            //Fix date format
            $pattern = '/\d{1,2}\s\w+\s\d{4}/'; // matches a date in the format "dd Month yyyy"

            $date = $timestamp;
            if (preg_match($pattern, $timestamp, $matches)) {
                $date = $matches[0];
            }

            // Add the review to the $reviews_array
            $reviews_array[] = [
                'id' => $id,
                'name' => $author,
                'rating' => $rating,
                'title' => $title,
                'description' => $content,
                'date' => $date,
                'is_verified' => $is_verified,
                'product_attributes' => $product_attributes,
            ];
        }

        return [
            'asin' => $asin,
            'original_url' => $original_url,
            'external_url'=>$external_url,
            'rating_counts' => $rating_counts,
            'overall_rating'=>$overall_rating,
            'reviews_array'=> $reviews_array
        ];
    }

    // function to extract ASIN from Amazon product URL
    private function getASIN($url)
    {
        $pattern = '/([A-Z0-9]{10})/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }

    // function to detect Amazon site from URL
    function getAmazonSite($url)
    {
        $pattern = '/www\.amazon\.([a-z\.]+)/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }


    public function getReviews($asin, $site)
    {
        $username = env('OXYLAB_USERNAME');
        $password = env('OXYLAB_PASSWORD');
        $client = new GuzzleClient();

        $params = [
            'source' => 'amazon_reviews',
            'domain' => $site,
            'query' => $asin,
            'parse' => true,
        ];

        $response = $client->request('POST', 'https://realtime.oxylabs.io/v1/queries', [
            'auth' => [$username, $password],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $params,
        ]);

        return $response->getBody()->getContents();
    }
}
