<?php
namespace App\Helpers;

/**
 * Class ElasticHelper
 *
 * @author   Matic Vrscaj
 */
class ElasticHelpers
{

    /**
     * Delete and create index
     */
    public static function recreateIndex()
    {

        $indexExists = \Elasticsearch::connection()->indices()->exists([
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve2")
        ]);
        if ($indexExists) {
            $deleteIndexArgs = [
                "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve2"),
            ];
            \Elasticsearch::connection()->indices()->delete($deleteIndexArgs);
        }

        $createIndexArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve2"),
        ];
        $createIndexArgs["body"] = <<<HERE
{
    "settings": {
        "number_of_shards": 1,
        "number_of_replicas": 0,
        "analysis": {
            "analyzer": {
                "lowercase_analyzer": {
                    "type": "custom",
                    "tokenizer": "standard",
                    "filter": [ "lowercase" ]
                }
            }
        }
    },
    "mappings": {
        "zrtev1": {
            "date_detection": false,
            "properties": {
                "PRIIMEK": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "PRIIMEK2": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "IME": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "ROJSTVO": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "ROJSTVO_LETO": {
                    "type": "integer"
                },
                "ROJSTVO_MESEC": {
                    "type": "integer"
                },
                "SMRT": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "SMRT_LETO": {
                    "type": "integer"
                },
                "SMRT_MESEC": {
                    "type": "integer"
                },
                "BIVALISCE": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "DEZELA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "ENOTA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "CIN": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "STARSI": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "DOMOVINSKA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "IZVOR": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "KRAJ_ROJSTVA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "KRAJ_SMRTI": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "OBCINA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "OPOMBE": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "OSTALO": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "POKOP": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "STAN": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "VIRI": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "VPOKLIC": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "VZROK": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "ZUPNIJA": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                },
                "LAST_MODIFIED": {
                    "type": "text",
                    "fields": {
                        "keyword": {
                            "type": "keyword"
                        }
                    }
                }
            }
        }
    }
}
HERE;

        return \Elasticsearch::connection()->indices()->create($createIndexArgs);

        /*
        $deleteIndexArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => "",
            "id" => "",
        ];
        \Elasticsearch::connection()->delete($deleteIndexArgs);

        $createIndexArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "id" => "",
            "body" => []
        ];
        return @\Elasticsearch::connection()->create($createIndexArgs);
        */
    }


    /**
     * Sends a document to elastic search to be indexed
     * @param $zrtevId Integer entity id to index
     * @param $body Array body to index
     * @return array
     */
    public static function indexZrtev($zrtevId, $body)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "id" => $zrtevId,
            "body" => $body
        ];
        return \Elasticsearch::connection()->index($requestArgs);
    }

    /**
     * Delete a document from elastic search index
     * @param $zrtevId Integer entity id to delete
     * @return array
     */
    public static function deleteZrtev($zrtevId)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "id" => $zrtevId,
        ];
        return \Elasticsearch::connection()->delete($requestArgs);
    }

    /**
     * Retrieves all matching documents from elastic search
     * @param $query String to match
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @return array
     */
    public static function search($query, $filter, $offset = 0, $limit = 10, $sortField = null, $sortOrder = "asc")
    {
        foreach ($filter as $key => $val) {
            $query .= " AND ".$key.":".$val;
        }

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "query" => [
                    "query_string" => [
                        "query" => $query,
                    ]
                ],
                /*
                "query" => [
                    "match" => [
                        "_all" => $query,
                    ]
                ],
                */
                //"filter" => $esFilter,
                //"sort" => "id",
                "from" => $offset,
                "size" => $limit,
            ]
        ];

        if ($sortField) {
            if ($sortField !== "ID") $sortField .= ".keyword";
            $requestArgs["body"]["sort"] = [
                [
                    $sortField => ["order" => $sortOrder]
                ]
            ];
        }

        return \Elasticsearch::connection()->search($requestArgs);
    }

    /**
     * Retrieves all matching documents from elastic search
     * @param $query String to match
     * @param $offset Integer offset
     * @param $limit Integer limit
     * @return array
     */
    /*
    public static function searchFilter($filter, $offset = 0, $limit = 10)
    {
        $must = [];
        foreach ($filter as $key => $val) {
            $must[] = ["term" => [$key => $val]];
        }

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "query" => [
                    "bool" => [
                        "must" => $must
                    ]
                ],
                //"sort" => "id",
                "from" => $offset,
                "size" => $limit,
            ]
        ];
        return \Elasticsearch::connection()->search($requestArgs);
    }
    */

    public static function searchByIdArray($idArray)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "query" => [
                    "ids" => [
                        "values" => $idArray
                    ]
                ]
            ]
        ];
        $dataElastic = \Elasticsearch::connection()->search($requestArgs);
        return self::mergeElasticResultAndIdArray($dataElastic, $idArray);
    }

    public static function searchById($idArray)
    {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "query" => [
                    "ids" => [
                        "values" => [ $idArray ]
                    ]
                ]
            ]
        ];
        $dataElastic = \Elasticsearch::connection()->search($requestArgs);
        return $dataElastic;
    }

    public static function distinctDezela() {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "aggs" => [
                    "uniq" => [
                        "terms" => [ "field" => "DEZELA.keyword" ]
                    ]
                ],
                "size" => 0,
            ]
        ];

        $elasticResult = \Elasticsearch::connection()->search($requestArgs);
        $result = [];
        if (isset($elasticResult["aggregations"]) && isset($elasticResult["aggregations"]["uniq"])) {
            $buckets = $elasticResult["aggregations"]["uniq"]["buckets"];
            foreach ($buckets as $idx => $bucket) {
                if (!$bucket["key"]) continue;
                $result[] = $bucket["key"];
            }
        }
        return $result;
    }

    public static function distinctObcina() {
        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "aggs" => [
                    "uniq" => [
                        "terms" => [ "field" => "OBCINA.keyword" ]
                    ]
                ],
                "size" => 0,
            ]
        ];

        $elasticResult = \Elasticsearch::connection()->search($requestArgs);
        $result = [];
        if (isset($elasticResult["aggregations"]) && isset($elasticResult["aggregations"]["uniq"])) {
            $buckets = $elasticResult["aggregations"]["uniq"]["buckets"];
            foreach ($buckets as $idx => $bucket) {
                if (!$bucket["key"]) continue;
                $result[] = $bucket["key"];
            }
        }
        return $result;
    }


    public static function searchChartData($from, $to, $land, $munic) {

        $fromYear = 1939;
        $fromMonth = 0;
        if ($from) {
            $e = explode("-", $from);
            $fromYear = intval($e[0]);
            $fromMonth = intval($e[1]);
        }

        $toYear = 1950;
        $toMonth = 12;
        if ($to) {
            $e = explode("-", $to);
            $toYear = intval($e[0]);
            $toMonth = intval($e[1]);
        }

        $requestArgs = [
            "index" => env("SI4_ELASTIC_ZRTVE_INDEX", "zrtve"),
            "type" => env("SI4_ELASTIC_ZRTVE_DOCTYPE", "zrtev"),
            "body" => [
                "aggs" => [
                    "year" => [
                        "terms" => [ "field" => "SMRT_LETO" ],
                        "aggs" => [
                            "month" => [
                                "terms" => [ "field" => "SMRT_MESEC" ],
                            ]
                        ]
                    ]
                ],
                "size" => 0,
            ]
        ];


        $mainBool = [
            "bool" => [
                "must" => [
                    // From
                    [
                        "bool" => [
                            "should" => [ // Either SMRT_LETO > $fromYear
                                ["range" => [
                                    "SMRT_LETO" => [
                                        "gt" => $fromYear,
                                    ]
                                ]],
                                ["bool" => [ // Or SMRT_LETO >= $fromYear AND SMRT_MESEC >= $fromMonth
                                    "must" => [
                                        ["range" => [
                                            "SMRT_LETO" => [
                                                "gte" => $fromYear,
                                            ]
                                        ]],
                                        ["range" => [
                                            "SMRT_MESEC" => [
                                                "gte" => $fromMonth,
                                            ]
                                        ]],
                                    ]
                                ]],
                            ],
                            "minimum_should_match" => 1
                        ]
                    ],
                    // To
                    [
                        "bool" => [
                            "should" => [ // Either SMRT_LETO < $toYear
                                ["range" => [
                                    "SMRT_LETO" => [
                                        "lt" => $toYear,
                                    ]
                                ]],
                                ["bool" => [ // Or SMRT_LETO >= $fromYear AND SMRT_MESEC >= $fromMonth
                                    "must" => [
                                        ["range" => [
                                            "SMRT_LETO" => [
                                                "lte" => $toYear,
                                            ]
                                        ]],
                                        ["range" => [
                                            "SMRT_MESEC" => [
                                                "lte" => $toMonth,
                                            ]
                                        ]],
                                    ]
                                ]],
                            ],
                            "minimum_should_match" => 1
                        ]
                    ],
                ],
            ]
        ];

        if ($land) {
            $mainBool["bool"]["must"][] = ["term" => [ "DEZELA.keyword" => $land ]];
        }

        if ($munic) {
            $mainBool["bool"]["must"][] = ["term" => [ "OBCINA.keyword" => $munic ]];
        }

        $requestArgs["body"]["query"] = $mainBool;

        $elasticResult = \Elasticsearch::connection()->search($requestArgs);
        //print_r($elasticResult);
        $result = [];
        if (isset($elasticResult["aggregations"]) && isset($elasticResult["aggregations"]["year"])) {
            $yearBuckets = $elasticResult["aggregations"]["year"]["buckets"];
            foreach ($yearBuckets as $yIdx => $yearBucket) {
                if (!$yearBucket["key"]) continue;
                $year = $yearBucket["key"];
                $monthBuckets = $yearBucket["month"]["buckets"];
                foreach ($monthBuckets as $mIdx => $monthBucket) {
                    $month = str_pad($monthBucket["key"], 2, "0", STR_PAD_LEFT);
                    $result[$year."-".$month] = $monthBucket["doc_count"];
                }
            }
        }

        return $result;
    }


    public static function elasticResultToAssocArray($dataElastic) {
        $result = [];
        if (isset($dataElastic["hits"]) && isset($dataElastic["hits"]["hits"])) {
            foreach ($dataElastic["hits"]["hits"] as $hit){
                $result[$hit["_id"]] = [
                    "id" => $hit["_id"],
                    "_source" => $hit["_source"],
                ];
            }
        }
        return $result;
    }

    public static function mergeElasticResultAndIdArray($dataElastic, $idArray) {
        $hits = self::elasticResultToAssocArray($dataElastic);

        $result = [];
        foreach ($idArray as $id) $result[$id] = ["id" => $id];
        foreach ($result as $i => $val) {
            if (isset($hits[$i])) $result[$i]["_source"] = $hits[$i]["_source"];
        }
        return $result;
    }

    public static function elasticResultToSimpleAssocArray($dataElastic) {
        $result = [];
        if (isset($dataElastic["hits"]) && isset($dataElastic["hits"]["hits"])) {
            foreach ($dataElastic["hits"]["hits"] as $hit){
                $result[$hit["_id"]] = $hit["_source"];
            }
        }
        return $result;
    }
    public static function elasticResultToSimpleArray($dataElastic) {
        $result = [];
        if (isset($dataElastic["hits"]) && isset($dataElastic["hits"]["hits"])) {
            foreach ($dataElastic["hits"]["hits"] as $hit){
                $result[] = $hit["_source"];
            }
        }
        return $result;
    }
}