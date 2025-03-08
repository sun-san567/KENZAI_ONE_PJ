<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;

class FileList extends Component
{
    public $project;
    public $files;

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->refreshFiles();
    }

    public function refreshFiles()
    {
        $this->files = $this->project->files()
            ->with('uploader')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.file-list');
    }
}
