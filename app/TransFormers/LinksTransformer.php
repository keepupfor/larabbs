<?php
namespace App\Transformers;

use App\Models\Link;
use League\Fractal\TransformerAbstract;

class LinksTransformer extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }

}
