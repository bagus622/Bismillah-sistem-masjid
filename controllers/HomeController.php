<?php
/**
 * Home Controller
 * Landing page for public visitors
 */

class HomeController extends Controller {
    
    // Landing page
    public function index() {
        $pageTitle = 'Bismillah - Sistem Manajemen Keuangan Masjid';
        $this->view('home/index', compact('pageTitle'), 'landing');
    }
    
    // Pricing page
    public function pricing() {
        $pageTitle = 'Pricing Plans - Bismillah';
        $this->view('home/pricing', compact('pageTitle'), 'landing');
    }
    
    // Features page
    public function features() {
        $pageTitle = 'Features - Bismillah';
        $this->view('home/features', compact('pageTitle'), 'landing');
    }
    
    // About page
    public function about() {
        $pageTitle = 'About Us - Bismillah';
        $this->view('home/about', compact('pageTitle'), 'landing');
    }
}
