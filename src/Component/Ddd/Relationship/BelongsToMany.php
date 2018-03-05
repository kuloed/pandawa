<?php
/**
 * This file is part of the Pandawa package.
 *
 * (c) 2018 Pandawa <https://github.com/bl4ckbon3/pandawa>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pandawa\Component\Ddd\Relationship;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as LaravelBelongsToMany;
use Pandawa\Component\Ddd\AbstractModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * @author  Iqbal Maulana <iq.bluejack@gmail.com>
 */
class BelongsToMany extends LaravelBelongsToMany
{
    /**
     * @var AbstractModel
     */
    protected $parent;

    /**
     * {@inheritdoc}
     */
    public function __construct(Builder $query, AbstractModel $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName = null)
    {
        parent::__construct($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedPivotKey, $relationName);
    }

    /**
     * {@inheritdoc}
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        $this->parent->addPendingAction(
            function () use ($id, $attributes, $touch) {
                parent::attach($id, $attributes, $touch);
            }
        );
    }

    public function syncWithoutDetaching($ids)
    {
        $this->parent->addPendingAction(
            function () use ($ids) {
                parent::syncWithoutDetaching($ids);
            }
        );
    }

    public function sync($ids, $detaching = true)
    {
        $this->parent->addPendingAction(
            function () use ($ids, $detaching) {
                parent::sync($ids, $detaching);
            }
        );
    }

    public function detach($ids = null, $touch = true)
    {
        $this->parent->addPendingAction(
            function () use ($ids, $touch) {
                parent::detach($ids, $touch);
            }
        );
    }
}
