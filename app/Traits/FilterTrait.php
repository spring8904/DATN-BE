<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FilterTrait
{
    public function filterTrait($filters, Request $request, $query)
    {

        // Mẫu filter $filters = [
        //     'status' => ['queryWhere' => '='], // dùng cho bảng của chính nó 
        //     'course_name_approved' => null, // dùng cho bảng quan hệ của nó dùng để tìm bằng LIKE
        //     'user_name_approved' => null,
        //     'approver_name_approved' => null,
        //     'course_price_approved' => ['attribute' => ['amount_min' => '>=', 'amount_max' => '<=']], dùng cho bảng quán hệ của nó nhưng nó phải so sánh với những điều kiện khác nhau.
        //     'request_date' => ['attribute' => ['request_start_date' => '>=', 'request_end_date' => '<=']],
        //dùng cho bảng của nó khi phải so sánh với nhiều trường khác nhau
        //     'approval_date' => ['filed' => ['approved_at', 'rejected_at'], 'attribute' => ['approval_start_date' => '>=', 'approval_end_date' => '<=']],
        //dùng cho bảng của nó sử dụng chung attribute
        // ];

        foreach ($filters as $filter => $value) {
            $filterValue = $request->input($filter);
            $elementFilter = explode('_', $filter);

            if (str_contains($filter, '_') && count($elementFilter) === 3) {
                $elementFilter = explode('_', $filter);
                $relation = $elementFilter[0];
                $field = $elementFilter[1];

                if (method_exists($query->getModel(), $relation)) {
                    if (!empty($value) && is_array($value) && !empty($value['attribute'])) {
                        $hasValidFilter = false;

                        foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                            $filterValue = $request->input($keyAttribute);
                            if ($filterValue !== null) {
                                $hasValidFilter = true;
                                break;
                            }
                        }
                        if ($hasValidFilter) {
                            $query->whereHas($relation, function ($query) use ($field, $value, $request) {
                                foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                    $filterValue = $request->input($keyAttribute);
                                    if ($filterValue !== null) {
                                        $query->where($field, $valueAttribute, $filterValue);
                                    }
                                }
                            });
                        }
                    } else {
                        if (!empty($filterValue)) {
                            $query->whereHas($relation, function ($query) use ($field, $filterValue) {
                                $query->where($field, 'LIKE', "%$filterValue%");
                            });
                        }
                    }
                }
            } else {
                if ($filterValue !== null) {
                    $operator = isset($value['queryWhere']) ? $value['queryWhere'] : '=';
                    $filterValue = ($operator === 'LIKE') ? "%$filterValue%" : $filterValue;
                    $query->where($filter, $operator, $filterValue);
                } else {
                    if (!empty($value['attribute']) && is_array($value['attribute'])) {
                        if (isset($value['filed']) && is_array($value['filed']) && sizeof($value['filed']) >= 1) {
                            $query->where(function ($query) use ($request, $value) {
                                foreach ($value['filed'] as $filed) {
                                    $query->orWhere(function ($query) use ($filed, $request, $value) {
                                        foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                            $filterValue = $request->input($keyAttribute);
                                            if ($filterValue !== null) {
                                                $query->where($filed, $valueAttribute, $filterValue);
                                            }
                                        }
                                    });
                                }
                            });
                        } else {
                            foreach ($value['attribute'] as $keyAttribute => $valueAttribute) {
                                $filterValue = $request->input($keyAttribute);
                                if (!empty($filterValue)) {
                                    $query->where(function ($query) use ($filter, $filterValue, $valueAttribute) {
                                        $query->where($filter, $valueAttribute, $filterValue);
                                    });
                                }
                            }
                        }
                    }
                }
            }
        }

        return $query;
    }
}
