<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Partner;
use App\Models\Section;
use App\Models\SiteSetting;
use App\Models\TeamMember;

class PageController extends Controller
{
    private function getSettings(): array
    {
        return SiteSetting::all()->pluck('value', 'key')->toArray();
    }

    private function getSections(string $page): array
    {
        $sections = Section::getPageSections($page);
        $sectionItems = [];
        foreach ($sections as $section) {
            $sectionItems[$section->key] = $section->activeItems;
        }
        return [$sections, $sectionItems];
    }

    public function home()
    {
        [$sections, $items] = $this->getSections('home');
        $settings = $this->getSettings();
        return view('pages.home', compact('sections', 'items', 'settings'));
    }

    public function about()
    {
        [$sections, $items] = $this->getSections('about');
        $teamMembers = TeamMember::where('is_active', true)->orderBy('order')->get();
        $settings = $this->getSettings();
        return view('pages.about', compact('sections', 'items', 'teamMembers', 'settings'));
    }

    public function programs()
    {
        [$sections, $items] = $this->getSections('programs');
        $settings = $this->getSettings();
        return view('pages.programs', compact('sections', 'items', 'settings'));
    }

    public function events()
    {
        [$sections, $items] = $this->getSections('events');
        $featuredEvent = Event::where('is_active', true)->where('is_featured', true)->first();
        $events = Event::where('is_active', true)->where('is_featured', false)->orderBy('order')->get();
        $upcomingEvents = Event::where('is_active', true)->where('date', '>=', now())->orderBy('date')->get();
        $settings = $this->getSettings();
        return view('pages.events', compact('sections', 'items', 'featuredEvent', 'events', 'upcomingEvents', 'settings'));
    }

    public function ourPartner()
    {
        [$sections, $items] = $this->getSections('mitra');
        $internationalPartners = Partner::where('is_active', true)->where('type', 'international')->orderBy('order')->get();
        $nationalPartners = Partner::where('is_active', true)->where('type', 'national')->orderBy('order')->get();
        $settings = $this->getSettings();
        return view('pages.ourpartner', compact('sections', 'items', 'internationalPartners', 'nationalPartners', 'settings'));
    }
}
