<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'detail' => 'json',
    ];

    public function extension()
    {
        return $this->hasOne(ProductExtension::class, 'product_id');
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getLatestProducts(int $limit)
    {
        return $this->with('images')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTopSellers(int $limit)
    {
        return $this->with('images')
            ->orderBy('sales', 'desc')
            ->limit($limit)
            ->get();
    }

    public function searchProducts($params)
    {
        // 解构参数
        [
            'category_id' => $categoryID,
            'page' => $page,
            'perPage' => $perPage,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'details' => $details,
        ] = $params + [
            'category_id' => null,
            'page' => 1,
            'perPage' => 2,
            'sortField' => null,
            'sortOrder' => null,
            'priceMin' => 0,
            'priceMax' => PHP_INT_MAX,
            'details' => [],
        ];

        // 计算从哪条记录开始
        $from = ($page - 1) * $perPage;

        // 构建查询条件
        $query = [
            'from' => $from,
            'size' => $perPage,
            '_source' => ['id', 'product_name', 'original_price', 'current_price', 'reviews_count', 'rating_avg', 'images'],
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'current_price' => [
                                    'gte' => $priceMin,
                                    'lte' => $priceMax,
                                ],
                            ],
                        ],
                        [
                            'bool' => [
                                'should' => [],
                            ],
                        ],
                    ],
                ],
            ],
            'aggs' => $this->getFilterList(), // 添加聚合查询
        ];

        // 添加排序参数
        if ($sortField && $sortOrder) {
            $query['sort'] = [
                [
                    $sortField => $sortOrder,
                ],
            ];
        }

        // 添加分类筛选条件
        if ($categoryID) {
            $query['query']['bool']['filter'] = [
                [
                    'term' => [
                        'categories.id' => $categoryID,
                    ],
                ],
            ];
        }

        // 添加详情筛选条件
        foreach ($params['details'] as $key => $value) {
            if ($key === 'carat_ranges') {
                // 解析区间
                [$gte, $lte] = explode('-', $value);

                $query['query']['bool']['must'][] = [
                    'range' => [
                        'extension.carat' => [
                            'gte' => $gte,
                            'lte' => $lte,
                        ],
                    ],
                ];
            } else {
                $query['query']['bool']['must'][] = [
                    'match' => [
                        "extension.$key" => $value,
                    ],
                ];
            }
        }

        // 执行搜索
        $response = app('elasticsearch')->search([
            'index' => 'products',
            'body' => $query,
        ]);

        // 错误处理
        if (!isset($response['hits'])) {
            throw new \RuntimeException('Failed to execute search.');
        }

        // 获取搜索结果
        $totalHits = $response['hits']['total']['value'] ?? 0;

        // 提取 '_source' 数据
        $products = array_map(function ($hit) {
            return $hit['_source'];
        }, $response['hits']['hits'] ?? []);

        // 计算总页数
        $totalPages = $totalHits > 0 ? ceil($totalHits / $perPage) : 1;

        // 从响应中获取聚合结果
        $aggregations = $response['aggregations'];
        $filterList = [];
        foreach ($aggregations as $key => $value) {
            foreach ($value['buckets'] as $bucket) {
                $filterList[$key][] = $bucket;
            }
        }

        // 排序筛选列表
        $order = ['price', 'stone cut', 'stone color', 'carat ranges', 'plating color', 'style', 'occasion', 'recipient'];
        uksort($filterList, function ($a, $b) use ($order) {
            $orderA = array_search($a, $order);
            $orderB = array_search($b, $order);

            // 将未出现在 $order 数组中的键排到最后
            $orderA = $orderA === false ? count($order) : $orderA;
            $orderB = $orderB === false ? count($order) : $orderB;

            return $orderA <=> $orderB;
        });

        // 返回结果
        return [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalHits' => $totalHits,
            'products' => $products,
            'filterList' => $filterList, // 返回排序后的筛选列表
        ];
    }

    private function getFilterList()
    {
        // 构建聚合查询体
        return [
            'price' => [
                'histogram' => [
                    'field' => 'current_price',
                    'interval' => 50,
                ],
            ],
            'carat ranges' => [
                'histogram' => [
                    'field' => 'extension.carat',
                    'interval' => 0.5,
                ],
            ],
            'stone cut' => [
                'terms' => [
                    'field' => 'extension.stone_cut.keyword',
                ],
            ],
            'stone color' => [
                'terms' => [
                    'field' => 'extension.stone_color.keyword',
                ],
            ],
            'plating color' => [
                'terms' => [
                    'field' => 'extension.plating_color.keyword',
                ],
            ],
            'style' => [
                'terms' => [
                    'field' => 'extension.style.keyword',
                ],
            ],
            'occasion' => [
                'terms' => [
                    'field' => 'extension.occasion.keyword',
                ],
            ],
            'recipient' => [
                'terms' => [
                    'field' => 'extension.recipient.keyword',
                ],
            ],
        ];
    }
}
