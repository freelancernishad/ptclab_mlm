<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdsSettings extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $adssetting;
    public function __construct($adssetting)
    {
        $this->adssetting = $adssetting;
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ads-settings');
    }
}
