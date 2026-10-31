<?php

namespace App\Models\Concerns;

trait HasPublishing
{
    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where($this->getTable().'.is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy($this->getTable().'.sort_order')->orderByDesc($this->getTable().'.id');
    }
}
