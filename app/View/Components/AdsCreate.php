<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AdsCreate extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $adssettings;
    public $type;
    public $adtype;
    public function __construct($adssettings,$type,$adtype)
    {
        $this->adssettings = $adssettings;
        $this->type = $type;
        $this->adtype = $adtype;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ads-create');
    }
}
