<?php

namespace App\View\Components\Member;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Reward;

/**
 * Class RewardCard
 *
 * Represents a reward card component in a view.
 */
class RewardCard extends Component
{
    // Public property for the component
    public $reward;

    /**
     * Create a new component instance.
     *
     * @param Reward|null $reward The reward model. Default is null.
     */
    public function __construct(Reward $reward = null)
    {
        $this->reward = $reward;
    }

    /**
     * Get the view or contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.member.reward-card');
    }
}
