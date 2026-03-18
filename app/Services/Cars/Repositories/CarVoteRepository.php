<?php

declare(strict_types=1);

namespace App\Services\Cars\Repositories;

use App\Services\Cars\Models\CarVote;
use FKS\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<CarVote>
 */
class CarVoteRepository extends Repository
{
    public static function getEntityInstance(): Model
    {
        return CarVote::make();
    }
}
