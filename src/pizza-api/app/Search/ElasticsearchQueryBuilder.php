<?php

namespace App\Search;
use Illuminate\Support\Arr;

trait ElasticsearchQueryBuilder
{

    public $query = [];

    public function search(int $perPage = 10, int $page = 1)
    {
        $items = $this->searchOnElasticsearch($perPage,$page);
        $total = $items["hits"]["total"];
        return ["data" => $this->buildCollection($items),"total" => $total];
    }

    public function searchOnElasticsearch(int $perPage, int $page = 1): array
    {
        $items = $this->elasticsearch->search([
            "size" => $perPage,
            "from" => (($page-1) * $perPage),
            'index' => $this->model->getSearchIndex(),
            'type' => $this->model->getSearchType(),
            'body' => $this->query
        ]);

        return $items;
    }

    public function buildCollection(array $items)
    {
        return Arr::pluck($items['hits']['hits'], '_source');
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return $this->model->findMany($ids)
            ->sortBy(function ($data) use ($ids) {
                return array_search($data->getKey(), $ids);
            });
    }

    public function createMultiMatchQuery(string $seach = '', array $fields = [])
    {
        $this->query = [
            'query' => [
                'multi_match' => [
                    'fields' => $fields,
                    'query' =>  $seach
                ]
            ]
        ];

        return $this;
    }

    public function createSingleMatchQuery(string $search = '', string $field)
    {
        $this->query = [
            'query' => [
                'match' => [
                    $field => [
                        "query" => $search
                    ]
                ]
            ]
        ];

        return $this;
    }

    public function createBoolQuery()
    {
        $this->query = [
            'query' => [
                "bool"=> [

                ]
            ]
        ];

        return $this;
    }

    public function addShouldQuery()
    {
        $this->query["query"]["bool"]["should"] = [ ] ;

        return $this;
    }

    public function addMinimumShouldMatch(int $min = 1)
    {
        $this->query["query"]["bool"]["minimum_should_match"] = $min;

        return $this;
    }


    public function addLikeToShouldQuery($search = [], array $fields = [])
    {
        $i = 0;

        foreach($fields as $field_name)
        {
            $this->query["query"]["bool"]["should"][$i] =  ["wildcard"=> [
                $field_name=> [
                  "value"=> "*".(is_array($search) ? $search[$i] : $search)."*"
                ]]
            ];

            $i++;
        }

        return $this;
    }

    public function addEqualToShouldQuery($search = [], array $fields = [])
    {
        $i = 0;

        foreach($fields as $field_name)
        {
            $this->query["query"]["bool"]["should"][$i] =  [
                "match"=>  [$field_name =>  (is_array($search) ? $search[$i] : $search)]
            ];

            $i++;
        }

        return $this;
    }

    public function addMustQuery()
    {
        $this->query["query"]["bool"]["must"] = [ ] ;

        return $this;
    }

    public function addLikeToMustQuery($search = [], array $fields = [])
    {
        $i = 0;

        foreach($fields as $field_name)
        {
            $this->query["query"]["bool"]["must"][$i] =  ["wildcard"=> [
                $field_name=> [
                  "value"=> "*".(is_array($search) ? $search[$i] : $search)."*"
                ]]
            ];

            $i++;
        }

        return $this;
    }

    public function addEqualToMustQuery($search = [], array $fields = [])
    {
        $i = 0;

        foreach($fields as $field_name)
        {
            $this->query["query"]["bool"]["must"][$i] =  [
                "match"=> [ $field_name=>  (is_array($search) ? $search[$i] : $search) ]
            ];

            $i++;
        }

        return $this;
    }


    public function addSortToQuery(array $sortBy)
    {
        $this->query["sort"] = [];

        foreach($sortBy as $value)
        {
            $this->query["sort"][] = $value;

        }

        return $this;
    }
}
