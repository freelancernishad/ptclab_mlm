<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserTreeView extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id;
    public $tree;

    public function __construct($id)
    {
        // $this->tree = $tree;
        $this->tree = getTree($id);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.user-tree-view');
    }
}
