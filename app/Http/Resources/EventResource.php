<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fiscal_year_id' => $this->fiscal_year_id,
            'company_id' => $this->company_id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'ad_date' => $this->ad_date,
            'time' => $this->time,
            'assignTo' => $this->assignTo,
            'status' => $this->status,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'fiscalYear' => new FiscalYearResource($this->whenLoaded('fiscalYear')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),


        ];
    }
}
