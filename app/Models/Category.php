<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Content;

class Category extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     */
    protected $fillable = [
        'content',
    ];

    /**
     * このカテゴリーに属するコンテンツを取得
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }
}
