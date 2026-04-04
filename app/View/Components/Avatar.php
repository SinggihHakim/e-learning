<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;
    public $size;
    public $initials;
    public $bgColor;

    public function __construct($user, $size = 40)
    {
        $this->user = $user;
        $this->size = $size;

        if ($user && isset($user->name) && trim($user->name) !== '') {
            $name = trim($user->name);
            
            // Extract initials naturally from up to the first 2 words
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $w) {
                if (mb_strlen($w) > 0) {
                    $initials .= mb_substr($w, 0, 1, 'UTF-8');
                }
                if (mb_strlen($initials) >= 2) break;
            }
            $this->initials = mb_strtoupper($initials, 'UTF-8');

            // Safely calculate background color preventing negative modulo
            $bgColors = ['#f87171', '#fb923c', '#fbbf24', '#a3e635', '#34d399', '#2dd4bf', '#38bdf8', '#818cf8', '#a78bfa', '#e879f9', '#fb7185'];
            $colorIndex = abs(crc32($name)) % count($bgColors);
            $this->bgColor = $bgColors[$colorIndex];
        } else {
            // Null-safe fallback
            $this->initials = '?';
            $this->bgColor = '#94a3b8'; // default gray wrapper
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.avatar');
    }
}
