<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Livewire\Component;

class AttributeForm extends Component
{
    public $attributeId;
    public $name;
    public $options = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'options.*' => 'string|max:255',
    ];

    public function mount($attributeId = null)
    {
        if ($attributeId) {
            $attribute = Attribute::with('options')->findOrFail($attributeId);
            $this->attributeId = $attribute->id;
            $this->name = $attribute->name;
            $this->options = $attribute->options->pluck('value')->toArray();
        } else {
            $this->options = [''];
        }
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save()
    {
        $this->validate();

        $attribute = Attribute::updateOrCreate(
            ['id' => $this->attributeId],
            ['name' => $this->name]
        );

        $attribute->options()->delete();
        foreach ($this->options as $option) {
            if (!empty($option)) {
                $attribute->options()->create(['value' => $option]);
            }
        }

        session()->flash('success', 'Attribute saved successfully.');
        return redirect()->route('attributes.index');
    }

    public function render()
    {
        return view('livewire.attribute-form');
    }
}
