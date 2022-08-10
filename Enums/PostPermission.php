<?php

namespace Modules\Blog\Enums;

use Modules\Morphling\Enums\HasValues;

enum PostPermission: string
{
    use HasValues;

    case All = 'posts.*';
    case  ViewAny = 'posts.viewAny';
    case  ViewOwned = 'posts.viewOwned';
    case  View = 'posts.view';
    case  Create = 'posts.create';
    case  Update = 'posts.update';
    case  Delete = 'posts.delete';
    case  Replicate = 'posts.replicate';
    case  Restore = 'posts.restore';
}
